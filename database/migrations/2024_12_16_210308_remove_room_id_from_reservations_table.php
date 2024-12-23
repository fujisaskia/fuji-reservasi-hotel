<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRoomIdFromReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Menghapus foreign key constraint terlebih dahulu
            if (Schema::hasColumn('reservations', 'room_id')) {
                $table->dropForeign(['room_id']); // Menghapus foreign key
            }
            
            // Menghapus kolom room_id setelah foreign key dihapus
            if (Schema::hasColumn('reservations', 'room_id')) {
                $table->dropColumn('room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Menambahkan kembali kolom room_id
            $table->unsignedBigInteger('room_id')->nullable();

            // Menambahkan foreign key jika diperlukan
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }
}
