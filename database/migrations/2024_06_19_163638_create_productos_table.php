<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
           
            $table->string('nombre');
            
            $table->text('descripcion');
            
            $table->decimal('precio');
           
            $table->integer('cantidad');
            
            $table->integer('vendidos');
            
            
            $table->unsignedBigInteger('id_tienda');
            $table->foreign('id_tienda')->references('id')->on('tiendas');
            
            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('categorias_productos');
            
            $table->unsignedBigInteger('id_usuario_venta')->nullable();
            $table->foreign('id_usuario_venta')->references('id')->on('users');
            
            $table->unsignedBigInteger('id_usuario_creacion');
            $table->foreign('id_usuario_creacion')->references('id')->on('users');
            
            $table->timestamp('fecha_venta')->nullable();

            $table->timestamp('fecha_registro')->nullable()->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
};