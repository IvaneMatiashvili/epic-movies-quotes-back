<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('likes', function (Blueprint $table) {
			$table->dropColumn('like');
		});
	}

	public function down()
	{
		Schema::table('likes', function (Blueprint $table) {
			$table->boolean('like')->default(false);
		});
	}
};
