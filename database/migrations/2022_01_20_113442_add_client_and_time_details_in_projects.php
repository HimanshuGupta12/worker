<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientAndTimeDetailsInProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //########### Create missing columns on Live Database start ##############
        /*We don't need to write reverse for these. Thease were created on the fly & don't have record previously. Project create & this class will handle all cases.*/
        if (!Schema::hasColumn('projects', 'description')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->text('description')->nullable()->after('address')->default(NULL);
            });
        }
        if (!Schema::hasColumn('projects', 'start_date')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('address');
            });
        }
        if (!Schema::hasColumn('projects', 'shift_start')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->time('shift_start')->nullable()->after('address');
            });
        }
        if (!Schema::hasColumn('projects', 'shift_end')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->time('shift_end')->nullable()->after('address');
            });
        }
        if (!Schema::hasColumn('projects', 'break_time')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('break_time')->nullable()->after('address');
            });
        }
        if (!Schema::hasColumn('projects', 'manager_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('manager_id')->nullable()->constrained('workers')->after('address')->default(NULL);
            });
        }
        if (!Schema::hasColumn('projects', 'allow_comments')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('allow_comments')->after('address')->default(0);
            });
        }
        if (!Schema::hasColumn('projects', 'allow_photos')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('allow_photos')->after('address')->default(0);
            });
        }
        if (!Schema::hasColumn('projects', 'is_explainer_text')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->integer('is_explainer_text')->after('address')->default(0);
            });
        }
        if (!Schema::hasColumn('projects', 'explainer_text')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->text('explainer_text')->nullable()->after('address')->default(NULL);
            });
        }
        
        //######## Create missing columns on Live Database end ##############
        
        Schema::table('projects', function (Blueprint $table) {
            $table->string('city', 255)->nullable()->after('address');
            $table->string('postcode', 255)->nullable()->after('address');
            $table->unsignedTinyInteger('add_client')->after('allow_photos')->default(0);
            $table->foreignId('client_id')->nullable()->constrained()->after('address')->default(NULL);
            $table->unsignedTinyInteger('add_economical_details')->after('allow_photos')->default(0);
            $table->string('payment_type', 255)->nullable()->after('address');
            $table->float('hourly_rate', 8, 2)->nullable()->after('address');
            $table->float('fixed_rate', 12, 2)->nullable()->after('address');
            $table->unsignedMediumInteger('total_hours')->nullable()->after('address');
            $table->string('status', 100)->after('address')->default('active');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*#######  Needed only on local & stage where following columns were created directly in DB without using migration  ########*/
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['description', 'start_date', 'shift_start', 'shift_end', 'break_time', 'manager_id', 'allow_comments', 'allow_photos', 'is_explainer_text','explainer_text']);
        });
        /*#######  Needed only on local & stage END  ########*/
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['city', 'postcode', 'add_client', 'client_id', 'add_economical_details',
                'payment_type', 'hourly_rate', 'fixed_rate', 'total_hours', 'status', 'deleted_at']);
        });
    }
}
