<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
           $table->id();
            $table->string('nama_kost');
            $table->text('alamat');
           $table->decimal('harga', 10, 2)->change();
            $table->enum('status', ['tersedia', 'ditempati'])->default('tersedia')->after('foto');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('kos', function (Blueprint $table) {
        $table->dropColumn('status');
        $table->integer('harga')->change(); // Kembalikan ke integer jika rollback
    });
}
};  
