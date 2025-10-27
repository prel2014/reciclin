<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoProductoController extends Controller
{
    /**
     * Ver todos los productos disponibles
     */
    public function index(Request $request)
    {
        $alumno = Auth::user();
        $misRecipuntos = $alumno->recipuntos ?? 0;

        // Filtros
        $buscar = $request->get('buscar');
        $orden = $request->get('orden', 'precio_asc');

        // Query base
        $query = Publicacion::where('status', 'activo')
            ->where('disponibilidad', '>', 0);

        // Buscar
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $buscar . '%');
            });
        }

        // Ordenar
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            case 'recientes':
                $query->orderBy('created_at', 'desc');
                break;
        }

        $productos = $query->paginate(12);

        // Productos que puedo canjear
        $productosQueAsalcanzar = $productos->filter(function($producto) use ($misRecipuntos) {
            return ($producto->precio ?? 0) <= $misRecipuntos;
        })->count();

        return view('alumno.productos.index', compact(
            'productos',
            'alumno',
            'misRecipuntos',
            'productosQueAsalcanzar',
            'buscar',
            'orden'
        ));
    }

    /**
     * Ver detalle de producto y solicitar canje
     */
    public function show($id)
    {
        $alumno = Auth::user();
        $misRecipuntos = $alumno->recipuntos ?? 0;

        $producto = Publicacion::where('cod_publicacion', $id)
            ->where('status', 'activo')
            ->firstOrFail();

        // Verificar si puedo canjearlo
        $puedoCanjear = ($producto->precio ?? 0) <= $misRecipuntos && ($producto->disponibilidad ?? 0) > 0;

        // Cuántos puedo canjear máximo
        $cantidadMaxima = min(
            floor($misRecipuntos / ($producto->precio ?? 1)),
            $producto->disponibilidad ?? 0
        );

        return view('alumno.productos.show', compact(
            'producto',
            'alumno',
            'misRecipuntos',
            'puedoCanjear',
            'cantidadMaxima'
        ));
    }

    /**
     * Solicitar canje de producto
     */
    public function solicitarCanje(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $alumno = Auth::user();
        $producto = Publicacion::where('cod_publicacion', $id)
            ->where('status', 'activo')
            ->firstOrFail();

        $cantidad = $request->cantidad;
        $precioTotal = ($producto->precio ?? 0) * $cantidad;

        // Validaciones
        if (($alumno->recipuntos ?? 0) < $precioTotal) {
            return back()->with('error', 'No tienes suficientes Recipuntos para este canje.');
        }

        if (($producto->disponibilidad ?? 0) < $cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        // Crear la compra (canje)
        $compra = Compra::create([
            'cod_usuario' => $alumno->cod_usuario,
            'cod_publicacion' => $producto->cod_publicacion,
            'cantidad' => $cantidad,
            'precio_t' => $precioTotal,
            'status' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Descontar recipuntos del alumno
        $alumno->recipuntos = ($alumno->recipuntos ?? 0) - $precioTotal;
        $alumno->save();

        // Reducir disponibilidad del producto
        $producto->disponibilidad = ($producto->disponibilidad ?? 0) - $cantidad;
        $producto->save();

        return redirect()->route('alumno.canjes.index')
            ->with('success', '¡Canje solicitado exitosamente! Tu producto está en proceso de entrega.');
    }
}
