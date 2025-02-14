<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-700 leading-tight">
                    PRODUCTOS
                </h1>

                @can('admin.products.files')
                    <x-jet-danger-button wire:click="$emit('deleteProduct')">
                        ELIMINAR
                    </x-jet-danger-button>    
                @endcan
                

            </div>

        </div>

    </header>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-700">
        <h1 class="text-3xl text-center font-semibold mb-8 border-b-2">CREAR UN PRODUCTO</h1>

        <div class="mb-4" wire:ignore>
            <form action="{{route('admin.products.files',$product)}}"
            method="POST"
                    class="dropzone"
                    id="my-awesome-dropzone">
            </form>
        </div>

        @if ($product->images->count())
                
            <section class="bg-white shadow-xl rounded-lg p-6 mb-4">
                <h1 class=" text-2xl text-center font-semibold mb-2">Imagenes del producto</h1>
                <ul class="flex flex-wrap   justify-center ">
                    @foreach ($product->images as $image)
                        <li class="relative justify-center" wire:key="'image-{{$image->id}}'">
                            <img class="w-28 lg:w-32 h-20 object-cover" src="{{Storage::url($image->url)}}" alt="">
                            <x-jet-danger-button 
                                    wire:loading.attr="disabled"
                                    wire:target="deleteImage({{$image->id}})"
                                    wire:click="deleteImage({{$image->id}})" class=" w-5 h-5 absolute right-1 top-1">
                                
                            </x-jet-danger-button>
                        </li>
                        
                    @endforeach
                </ul>            
            </section>
        @endif

        @livewire('admin.status-product', ['product' => $product], key('status-product-'.$product->id))

        <div class="bg-white shadow-xl rounded-lg p-6">
            <div class="grid grid-cols-2 gap-6">

                {{-- CATEGORIA --}}
                <div>
                    <x-jet-label value="Categorias" />

                    <select class="w-full form-control capitalize" wire:model="category_id">
                        <option value="" selected disabled>Seleccione una categoria</option>
                        @foreach ($categories as $category)
                            <option class="capitalize" value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="category_id" />
                </div>

                {{-- SUBCATEGORIA --}}
                <div class="mb-4">
                    <x-jet-label value="Sub Categorias" />

                    <select class="w-full form-control capitalize" wire:model="product.subcategory_id">
                        <option  value="" selected disabled>Seleccione una sub categoria</option>
                        @foreach ($subcategories as $subcategory)
                            <option class="capitalize" value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="product.subcategory_id" />
                </div>
            </div>
            
            {{-- NOMBRE DEL PRODUCTO --}}
            <div class="mb-4">
                <x-jet-label value="Nombre" />
                <x-jet-input wire:model="product.name" class="w-full uppercase" type="text" placeholder="Ingrese nombre del producto" />

                <x-jet-input-error for="product.name" />
            </div>

            {{-- SLUG DEL PRODUCTO --}}
            <div class="mb-4">
                <x-jet-label value="Slug" />
                <x-jet-input wire:model="slug" class="w-full bg-gray-200" disable type="text" placeholder="Ingrese slug del producto" />

                <x-jet-input-error for="slug" />
            </div>

            {{-- DESCRIPCION DEL PRODUCTO --}}
            <div class="mb-4">
                <div wire:ignore>
                    <x-jet-label value="Descripción" />
                    <textarea wire:model="product.description" x-data x-init="ClassicEditor
                    .create( $refs.miEditor ).then(function(editor){
                        editor.model.document.on('change:data',() =>{
                            @this.set('product.description',editor.getData())
                        })
                    })
                    .catch( error => {
                        console.error( error );
                    } );" x-ref="miEditor" class="w-full form-control" rows="4">

                    </textarea>            
                </div>

                <x-jet-input-error for="product.description" />
            </div>

            <div class="grid grid-cols-2 mb-4 gap-6">
                {{-- MARCA --}}
                <div class="">
                    <x-jet-label value="Marca" />

                    <select wire:model="product.brand_id" class="form-control w-full capitalize">
                        <option value="" disabled selected>Seleccione un marca</option>
                        @foreach ($brands as $brand)
                            <option class="capitalize" value="{{$brand->id}}">{{$brand->name}}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="product.brand_id" />
                </div>

                {{-- PRECIO --}}
                <div>
                    <x-jet-label value="Precio" />
                    <x-jet-input wire:model="product.price" class="w-full form-control" step=".01" type="number" />

                    <x-jet-input-error for="product.price" />
                </div>

                {{-- SI EXISTE LA PROPIEDAD COMPUTADA updatedCategoryId -> SOLO SE REFERENCIA AL NOMBRE SUBCATEGORY  --}}
                @if ($this->subcategory)            
                    @if (!$this->subcategory->color && !$this->subcategory->size)
                        {{-- PRECIO --}}
                        <div>
                            <x-jet-label value="Cantidad" />
                            <x-jet-input wire:model="product.quantity" class="w-full form-control" type="number" />

                            <x-jet-input-error for="product.quantity" />
                        </div>
                                
                    @endif
                @endif
                
            </div>

            <div class="flex justify-end items-center mt-3">

                <x-jet-action-message class="mr-3 text-red-500" on="saved">
                    Registro Actualizado
                </x-jet-action-message>

                @can('admin.products.edit')                                    
                    <x-jet-button 
                            wire:loading.attr="disabled"
                            wire:target="save"
                            wire:click="save"                    
                            {{-- wire:click="$emit('save')" --}}
                            >
                        Actualizar Producto
                    </x-jet-button>
                @endcan
            </div>
        </div>

        @if ($this->subcategory)
            @if ($this->subcategory->size)
                <div class="{{$this->cambio == false? 'hidden' : ''}}">
                    @livewire('admin.size-product', ['product' => $product], key('size-product-'.$product->id))    
                </div>
                
            @elseif($this->subcategory->color)
                <div class="{{$this->cambio == false? 'hidden' : ''}}">
                    @livewire('admin.color-product', ['product' => $product], key('color-product-'.$product->id))
                </div>

            @endif
            
        @endif


        {{-- SWEET ALERT PARA EL METODO SAVE --}}

        
        @section('js')
            @include('admin.alerta-global.alertas')
        @endsection        

    </div>

    @push('script')
            <script>
                Dropzone.options.myAwesomeDropzone = {
                    headers: {
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}"
                    },
                    dictDefaultMessage: 'Arrastre una imagen al recuadro',
                    acceptedFiles: 'image/*',
                    paramName: "file", // The name that will be used to transfer the file
                    maxFilesize: 2, // MB
                    complete: function(file){
                        this.removeFile(file);
                    },
                    queuecomplete: function(){
                        Livewire.emit('refreshProduct');
                    }
                };

                Livewire.on('deleteSize', sizeId =>{            
                    Swal.fire({
                    title: 'Desea Eliminar la Talla?',
                    text: "No podra recuperar el registro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.size-product','delete',sizeId)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'La talla ha sido Eliminada'
                        })
                    }
                    })
                })

                Livewire.on('deletePivot', pivot =>{            
                    Swal.fire({
                    title: 'Desea Eliminar el Color?',
                    text: "No podra recuperar el registro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.color-product','delete',pivot)
                        
                        const Toast = Swal.mixin({
                                toast: true,
                                position: 'center',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'El Color ha sido Eliminado'
                        })
                    }
                    })
                })

                Livewire.on('deleteColorSize', pivot =>{            
                    Swal.fire({
                    title: 'Desea Eliminar el Color de esta Talla?',
                    text: "No podra recuperar el registro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.color-size','delete',pivot)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'El color y Stock ha sido eliminado'
                        })
                        
                    }
                    })
                })


                Livewire.on('deleteProduct', () =>{            
                    Swal.fire({
                    title: 'Desea Eliminar el Producto?',
                    text: "No podra recuperar el registro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Eliminar!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('admin.edit-product','delete')
                        /* Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Eliminado Correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        }) */
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'El Producto ha sido Eliminado'
                        })
                    }
                    })
                })
            </script>
            
        @endpush
</div>