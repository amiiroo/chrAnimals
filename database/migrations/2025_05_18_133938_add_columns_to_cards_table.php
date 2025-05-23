<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cards')) {
            Schema::create('cards', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->text('dop_info')->nullable();
                $table->string('kind');
                $table->string('region');
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('cards', function (Blueprint $table) {
                if (!Schema::hasColumn('cards', 'image')) {
                    $table->string('image')->nullable();
                }
                if (!Schema::hasColumn('cards', 'status')) {
                    $table->string('status')->nullable();
                }
                if (!Schema::hasColumn('cards', 'population')) {
                    $table->string('population')->nullable();
                }
                if (!Schema::hasColumn('cards', 'habitat')) {
                    $table->string('habitat')->nullable();
                }
                if (!Schema::hasColumn('cards', 'threats')) {
                    $table->text('threats')->nullable();
                }
                if (!Schema::hasColumn('cards', 'conservation')) {
                    $table->text('conservation')->nullable();
                }
                
                // Проверяем наличие timestamps и deleted_at
                if (!Schema::hasColumn('cards', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('cards', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
                if (!Schema::hasColumn('cards', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cards')) {
            Schema::table('cards', function (Blueprint $table) {
                $table->dropColumn([
                    'image', 
                    'status', 
                    'population', 
                    'habitat', 
                    'threats', 
                    'conservation'
                ]);
                
                if (Schema::hasColumn('cards', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};