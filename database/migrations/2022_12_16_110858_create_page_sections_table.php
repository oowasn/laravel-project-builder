<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreatePageSectionsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('page_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('page_id');
                $table->foreignId('section_id');
                $table->integer('rang')->default(100);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('page_sections');
        }
    }