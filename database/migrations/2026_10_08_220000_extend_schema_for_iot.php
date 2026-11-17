<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extend the existing schema with IoT assembly-line monitoring capabilities.
     *
     * Adds: workstations, assembly_instructions, sensor_readings,
     *       weight_validations, alerts, device_commands.
     * Modifies: users (role), products (weight fields), steps (weight fields).
     * Fixes: occurrences.end nullable syntax bug (already migrated with bug,
     *        so we alter it here).
     */
    public function up(): void
    {
        // 1. Add role to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('operator')->after('email');
        });

        // 2. Add weight validation fields to products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('expected_weight_grams', 10, 2)->nullable()->after('description')
                ->comment('Expected final product weight in grams');
            $table->decimal('weight_tolerance_grams', 10, 2)->nullable()->after('expected_weight_grams')
                ->comment('Acceptable deviation ± in grams');
        });

        // 3. Add weight fields to steps
        Schema::table('steps', function (Blueprint $table) {
            $table->decimal('expected_weight_grams', 10, 2)->nullable()->after('description')
                ->comment('Expected weight after this step completes');
            $table->decimal('weight_tolerance_grams', 10, 2)->nullable()->after('expected_weight_grams');
            $table->boolean('requires_weight_validation')->default(false)->after('weight_tolerance_grams');
            $table->text('instructions')->nullable()->after('requires_weight_validation')
                ->comment('Detailed assembly instructions for operator');
            $table->text('components_needed')->nullable()->after('instructions')
                ->comment('JSON list of components needed for this step');
        });

        // 4. Workstations table — extends production_lines with device/sensor info
        Schema::create('workstations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_line_id')
                ->constrained('production_lines')
                ->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('code', 50)->unique()
                ->comment('Unique station identifier e.g. bancada_01');
            $table->string('mqtt_topic_telemetry', 255)->nullable()
                ->comment('MQTT topic for receiving telemetry');
            $table->string('mqtt_topic_command', 255)->nullable()
                ->comment('MQTT topic for sending commands');
            $table->boolean('has_scale')->default(false);
            $table->boolean('has_led')->default(false);
            $table->string('status', 30)->default('offline')
                ->comment('online, offline, idle, busy, error');
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 5. Assembly instructions (visual guidance per step)
        Schema::create('assembly_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')
                ->constrained('steps')
                ->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image_path', 500)->nullable()
                ->comment('Path or URL to illustration');
            $table->timestamps();
        });

        // 6. Sensor readings
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstation_id')
                ->constrained('workstations')
                ->cascadeOnDelete();
            $table->foreignId('production_id')
                ->nullable()
                ->constrained('productions')
                ->nullOnDelete();
            $table->string('sensor_type', 50)->default('load_cell')
                ->comment('load_cell, ultrasonic, etc.');
            $table->decimal('value', 12, 4)
                ->comment('Raw sensor value');
            $table->string('unit', 20)->default('g');
            $table->boolean('is_stable')->default(false)
                ->comment('Whether the reading was stable');
            $table->boolean('is_simulated')->default(false);
            $table->json('raw_payload')->nullable()
                ->comment('Original MQTT payload for debugging');
            $table->string('mqtt_topic', 255)->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
        });

        // 7. Weight validations
        Schema::create('weight_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')
                ->constrained('productions')
                ->cascadeOnDelete();
            $table->foreignId('step_id')
                ->nullable()
                ->constrained('steps')
                ->nullOnDelete();
            $table->foreignId('workstation_id')
                ->constrained('workstations')
                ->cascadeOnDelete();
            $table->foreignId('sensor_reading_id')
                ->nullable()
                ->constrained('sensor_readings')
                ->nullOnDelete();
            $table->decimal('measured_weight', 10, 2)
                ->comment('Weight measured in grams');
            $table->decimal('expected_weight', 10, 2)
                ->comment('Expected weight at time of validation');
            $table->decimal('tolerance', 10, 2)
                ->comment('Tolerance ± at time of validation');
            $table->decimal('deviation', 10, 2)
                ->comment('Absolute difference from expected');
            $table->string('result', 20)
                ->comment('approved, rejected, invalid, error');
            $table->string('rejection_reason', 255)->nullable()
                ->comment('Human-readable reason for rejection');
            $table->boolean('is_simulated')->default(false);
            $table->timestamp('validated_at');
            $table->timestamps();
        });

        // 8. Alerts
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstation_id')
                ->nullable()
                ->constrained('workstations')
                ->nullOnDelete();
            $table->foreignId('production_id')
                ->nullable()
                ->constrained('productions')
                ->nullOnDelete();
            $table->string('type', 80)
                ->comment('weight_out_of_range, station_offline, mqtt_disconnected, etc.');
            $table->string('severity', 20)
                ->comment('info, warning, critical');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->boolean('acknowledged')->default(false);
            $table->foreignId('acknowledged_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('acknowledged_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. Device commands (remote hardware control tracking)
        Schema::create('device_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstation_id')
                ->constrained('workstations')
                ->cascadeOnDelete();
            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('command_type', 50)
                ->comment('tare, led_on, led_off');
            $table->json('payload')
                ->comment('JSON payload sent to device');
            $table->string('mqtt_topic', 255);
            $table->string('status', 30)->default('pending')
                ->comment('pending, published, acknowledged, failed, timeout');
            $table->text('response')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('timeout_at')->nullable();
            $table->timestamps();
        });

        // 10. Fix occurrences.end column (original migration has ->nullable without ())
        // The column was created but may have incorrect nullability
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dateTime('end')->nullable()->change();
        });

        // 11. Link steps to workstations
        Schema::table('steps', function (Blueprint $table) {
            $table->foreignId('workstation_id')
                ->nullable()
                ->after('production_line_id')
                ->constrained('workstations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropConstrainedForeignId('workstation_id');
        });

        Schema::dropIfExists('device_commands');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('weight_validations');
        Schema::dropIfExists('sensor_readings');
        Schema::dropIfExists('assembly_instructions');
        Schema::dropIfExists('workstations');

        Schema::table('steps', function (Blueprint $table) {
            $table->dropColumn(['expected_weight_grams', 'weight_tolerance_grams', 'requires_weight_validation', 'instructions', 'components_needed']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['expected_weight_grams', 'weight_tolerance_grams']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
