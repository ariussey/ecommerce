<div>
    <x-jet-dropdown width='96'>
        {{-- ICONO CARRITO DE COMPRAS --}}
        <x-slot name="trigger">
            <span class="relative inline-block cursor-pointer">
                <x-cart color='white' size='30' />
                    @if (Cart::count())
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{Cart::count()}}</span>                    
                    @else
                    <span class="absolute top-0 right-0 inline-block w-2 h-2 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                    @endif
                
            </span>                        
        </x-slot>

        {{-- CONTENIDO DEL CARRITO DE COMPRAS --}}
        <x-slot name="content">

            <ul class="{{ Cart::content()->count() > 4 ? 'overflow-y-auto h-80' : ''}}">
                
                @forelse (Cart::content() as $item)
                
                    <li class="flex p-2 border-b border-gray-200">
                        <img class="h-15 w-20 object-cover mr-4 object-center " src="{{$item->options->image}}" alt="">
                        

                        <article class="flex-1">
                            <h1 class="font-bold">{{$item->name}}</h1>
                            <div class="flex items-center">
                                <p>Cant: {{$item->qty}}</p>
                                @isset($item->options['color'])
                                    <p class="mx-2">- Color: {{-- {{$item->options['color']}} --}} <i class="fas fa-circle text-{{$item->options['color']}}-600 {{$item->options['color'] == 'white' ? 'text-white rounded-full border-2' : ''}}"></i></p>
                                @endisset

                                @isset($item->options['size'])
                                    <p>{{$item->options['size']}} </p>
                                @endisset
                            </div>
                            
                            <p>S/. {{$item->price * $item->qty}}</p>
                        </article>
                    </li>
                    
                @empty 
                    <li class="py-6 px-4">
                        <p class="text-center text-gray-700 ">
                            No tiene agregado ningún item en el carrito
                        </p>
                    </li>
                    
                @endforelse
            </ul>

            @if (Cart::count())
                <div class="p-2 py-2 px-3">
                    <p class="text-lg text-gray-700 mt-2 mb-3"><span class="font-bold">Total : </span> S/. {{Cart::subtotal()}}</p>
                    
                    <x-button-enlace href="{{ route('shopping-cart') }}" class="w-full" color="orange">
                        Ir al carrito de compras
                    </x-button-enlace>

                </div>

                
                
            @endif
            
            
        </x-slot>
    </x-jet-dropdown>
</div>
