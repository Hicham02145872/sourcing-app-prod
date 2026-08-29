<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area with soft shadow and backdrop blur -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-20 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md shadow-orange-500/20">
                            <!-- Pen/Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Update Quotation') }}</h1>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded border border-slate-200 uppercase tracking-wider">
                                    {{ __('Quote ID') }}: #{{ $quotation->display_id }}
                                </span>
                            </div>
                            <nav class="flex items-center text-xs text-slate-500 mt-1" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Dashboard') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Quotations') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="font-semibold text-orange-600">{{ __('Edit Quote') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50/50 border border-blue-100 rounded-lg text-blue-700 shadow-sm shadow-blue-500/5">
                            <span class="inline-flex items-center justify-center w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider">{{ __('Editing Mode') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form id="quotation-edit-form" method="POST" action="{{ route('admin.quotations.update', $quotation) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Sourcing Request Details (cols: 5) -->
                    @php $sourcingRequest = $quotation->sourcingRequest; @endphp
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Product Specs Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Product Specifications') }}</h3>
                                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                            </div>
                            <div class="p-6 space-y-5 text-sm">
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Product Name') }}</label>
                                    <div class="text-base font-bold text-slate-800 leading-snug">{{ $sourcingRequest->product_name }}</div>
                                </div>
                                
                                @if($sourcingRequest->product_url)
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">{{ __('Reference Link') }}</label>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100/80 text-blue-600 hover:text-blue-700 font-medium rounded-lg text-xs transition-all border border-blue-100 max-w-full overflow-hidden truncate">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span class="truncate block">{{ $sourcingRequest->product_url }}</span>
                                    </a>
                                </div>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Category') }}</label>
                                        <div class="font-semibold text-slate-700 bg-slate-100/60 px-2.5 py-1 rounded-md inline-block text-xs">{{ $sourcingRequest->category?->name ?? __('Unclassified') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Requested Location') }}</label>
                                        <div class="font-semibold text-slate-700 capitalize flex items-center gap-1.5 text-xs">
                                            <span class="inline-block w-2 h-2 rounded-full bg-slate-400"></span>
                                            {{ $sourcingRequest->sourcing_location }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($sourcingRequest->note)
                                <div class="pt-2 border-t border-slate-100">
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">{{ __('Client Notes') }}</label>
                                    <div class="p-4 bg-orange-50/30 rounded-xl border border-orange-100/60 text-slate-700 italic relative leading-relaxed text-xs">
                                        <span class="absolute right-3 bottom-1.5 text-orange-200 select-none text-2xl font-serif leading-none">”</span>
                                        {{ $sourcingRequest->note }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Image Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('Requested Product Image') }}</label>
                            @if($sourcingRequest->product_image)
                                <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-50 max-h-80 flex items-center justify-center shadow-inner">
                                    <img src="{{ media_url($sourcingRequest->product_image) }}" 
                                         class="w-full h-auto object-cover max-h-80 transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="px-3 py-1.5 bg-white/90 backdrop-blur text-xs font-semibold rounded-lg text-slate-700 shadow-sm">
                                            {{ __('Zoom View') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="h-36 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center text-slate-400 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium">{{ __('No image uploaded') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Destinations Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Destinations & Quantities') }}</h3>
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-extrabold rounded-md shadow-sm">
                                    {{ $sourcingRequest->destinations->count() }} {{ __('Routes') }}
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Country') }}</th>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Service') }}</th>
                                            <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($sourcingRequest->destinations as $dest)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-3.5 text-sm text-slate-800 font-semibold">
                                                <span class="fi fi-{{ strtolower($dest->country->code) }} w-5 h-4 inline-block align-middle rounded-sm shadow-sm mr-2 border border-slate-100"></span>
                                                <span class="align-middle">{{ $dest->country->name }}</span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-slate-500">
                                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200/60">
                                                    {{ $dest->service->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-right font-mono font-bold text-slate-800">
                                                {{ number_format($dest->quantity) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Client Profile Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <h3 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-4">{{ __('Client Profile') }}</h3>
                            <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="h-11 w-11 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-orange-700 flex items-center justify-center font-extrabold text-base border border-orange-200 shadow-sm">
                                    {{ substr($sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $sourcingRequest->user->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sourcing Quotation Form -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Quotation Details') }}</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Update the financial and logistical data as requested.') }}</p>
                            </div>

                    <div class="p-6 space-y-8">
                        
                        @include('admin.quotations.partials.currency-location', [
                            'currencyValue' => $quotation->currency,
                            'sourcingLocationValue' => $quotation->actual_sourcing_location,
                        ])

                        @include('admin.quotations.partials.sourcing-note', [
                            'sourcingLocationValue' => $quotation->actual_sourcing_location,
                            'requestedLocation' => $quotation->sourcingRequest->sourcing_location,
                            'sourcingNoteValue' => $quotation->sourcing_note,
                        ])

                        @include('admin.quotations.partials.supplier-comments', [
                            'supplierUrlValue' => $quotation->supplier_url,
                            'commentsValue' => $quotation->comments,
                        ])

                        @include('admin.quotations.partials.negotiation-reply', [
                            'negotiationNotes' => $quotation->negotiation_notes,
                            'negotiationReplyValue' => $quotation->admin_negotiation_reply,
                        ])

                        <!-- Subsection: Real Quality Image -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Real Product Quality Image') }}</h4>
                                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded animate-pulse">{{ __('Action Required') }}</span>
                            </div>

                            <div class="p-4 bg-red-50/50 border border-red-100 rounded-lg">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div class="space-y-2" x-data="{
                                                featuredPreview: null,
                                                handleFeaturedFile(files) {
                                                    if(files.length === 0) return;
                                                    const file = files[0];
                                                    if (file.size > 20971520) { // 20MB
                                                        window.dispatchEvent(new CustomEvent('show-error-toast', {
                                                            detail: `{{ __('Le fichier dépasse 20 MB.') }}`
                                                        }));
                                                        return;
                                                    }
                                                    const isVideo = file.type.startsWith('video/');
                                                    if (isVideo) {
                                                        const url = URL.createObjectURL(file);
                                                        this.featuredPreview = { src: url, file: file, isVideo: true, name: file.name };
                                                    } else {
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            this.featuredPreview = { src: e.target.result, file: file, isVideo: false, name: file.name };
                                                        };
                                                        reader.readAsDataURL(file);
                                                    }
                                                    const dt = new DataTransfer();
                                                    dt.items.add(file);
                                                    this.$refs.featuredInput.files = dt.files;
                                                },
                                                removeFeatured() {
                                                    if (this.featuredPreview && this.featuredPreview.isVideo && this.featuredPreview.src.startsWith('blob:')) {
                                                        URL.revokeObjectURL(this.featuredPreview.src);
                                                    }
                                                    this.featuredPreview = null;
                                                    this.$refs.featuredInput.value = '';
                                                }
                                            }">
                                                <label for="real_product_image" class="block text-[10px] font-bold text-red-600 uppercase">
                                                    {{ __('Featured Product Photo') }} <span class="text-red-400 font-normal">({{ __('Shows on dashboard/refunds') }})</span>
                                                </label>
                                                <div class="relative group">
                                                    <input type="file" x-ref="featuredInput" name="real_product_image" id="real_product_image" accept="image/*,video/*"
                                                        @change="handleFeaturedFile($event.target.files)"
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer bg-white border border-slate-200 p-2 rounded-md">
                                                </div>
                                                <template x-if="featuredPreview">
                                                    <div class="relative mt-2 w-24 h-24 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                                                        <template x-if="featuredPreview.isVideo">
                                                            <video :src="featuredPreview.src" class="w-full h-full object-cover" muted></video>
                                                        </template>
                                                        <template x-if="!featuredPreview.isVideo">
                                                            <img :src="featuredPreview.src" class="w-full h-full object-cover">
                                                        </template>
                                                        <button type="button" @click.stop="removeFeatured()"
                                                            class="absolute -top-1.5 -right-1.5 z-10 w-5 h-5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow flex items-center justify-center opacity-70 hover:opacity-100 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="space-y-2 col-span-1 md:col-span-2">
                                                <label for="supplier_url" class="block text-[10px] font-bold text-slate-600 uppercase">
                                                    {{ __('Supplier Product Link') }} <span class="text-slate-400 font-normal">({{ __('Internal only') }})</span>
                                                </label>
                                                <div class="relative group">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                    </div>
                                                    <input type="url" name="supplier_url" id="supplier_url" value="{{ old('supplier_url', $quotation->supplier_url) }}" placeholder="https://item.taobao.com/..."
                                                        class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors placeholder:text-slate-400">
                                                </div>
                                                @error('supplier_url')
                                                    <p class="text-[10px] text-red-600 mt-1 font-medium">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <p class="mt-2 text-[10px] text-red-500/80 font-medium italic">
                                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Téléchargez des photos et vidéos supplémentaires. Les médias existants peuvent être gérés ci-dessous. Taille maximale : 20 MB par fichier. Glisser-déposer supporté.') }}
                                        </p>
                                        <div id="quotation-file-size-error" class="mt-3 p-3 rounded-lg bg-red-100 border border-red-300 text-red-800 text-sm font-medium {{ $errors->has('real_product_image') || $errors->has('media_files') ? '' : 'hidden' }}" role="alert">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div>
                                                    @if($errors->has('real_product_image'))
                                                        <strong>{{ __('Erreur :') }}</strong> {{ $errors->first('real_product_image') }}
                                                    @elseif($errors->has('media_files'))
                                                        <strong>{{ __('Erreur :') }}</strong> {{ $errors->first('media_files') }}
                                                    @else
                                                        <strong>{{ __('Erreur :') }}</strong> {{ __('Un ou plusieurs fichiers dépassent 20 MB. Veuillez choisir des fichiers plus petits.') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="image-preview-container" class="{{ $quotation->real_product_image ? '' : 'hidden' }}">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">{{ __('Preview') }}</p>
                                        <div class="flex items-start gap-3">
                                            @php
                                                $isRealVideo = $quotation->real_product_image && in_array(strtolower(pathinfo($quotation->real_product_image, PATHINFO_EXTENSION)), ['mp4', 'mov', 'avi', 'webm']);
                                            @endphp
                                            @if($isRealVideo)
                                                <div class="h-24 w-24 rounded-lg border-2 border-red-200 border-dashed overflow-hidden bg-black shadow-sm flex items-center justify-center relative">
                                                    <video src="{{ media_url($quotation->real_product_image) }}" class="w-full h-full object-cover" muted controls></video>
                                                </div>
                                            @else
                                                <x-photo-viewer src="{{ media_url($quotation->real_product_image) }}" alt="Preview">
                                                    <div class="h-24 w-24 rounded-lg border-2 border-red-200 border-dashed overflow-hidden bg-white shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
                                                        <img id="image-preview" src="{{ $quotation->real_product_image ? media_url($quotation->real_product_image) : '#' }}" alt="Preview" class="h-full w-full object-cover">
                                                    </div>
                                                </x-photo-viewer>
                                            @endif
                                            @if($quotation->real_product_image)
                                                <button type="button"
                                                    class="mt-1 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors"
                                                    onclick="if(confirm('{{ __('Delete this featured photo?') }}')) { fetch('{{ route('admin.quotations.featured-photo.destroy', $quotation) }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => { if(r.ok) location.reload(); }); }">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    {{ __('Delete') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        @php
                            $qualityOptionsData = is_array($quotation->quality_options) ? $quotation->quality_options : [];
                            $qualityPriceValues = [];
                            $qualityWeightValues = [];
                            $qualityWeightUnits = [];
                            $qualityExistingImages = [];
                            foreach (['low', 'medium', 'good'] as $q) {
                                $qualityPriceValues[$q] = $qualityOptionsData[$q]['price'] ?? '';
                                $qualityWeightValues[$q] = $qualityOptionsData[$q]['weight'] ?? '';
                                $qualityWeightUnits[$q] = $qualityOptionsData[$q]['weight_unit'] ?? 'g';
                                $path = $qualityOptionsData[$q]['image_path'] ?? null;
                                $paths = $qualityOptionsData[$q]['image_paths'] ?? ($path ? [$path] : []);
                                $qualityExistingImages[$q] = $paths;
                            }
                        @endphp
                        @include('admin.quotations.partials.quality-options', [
                            'qualityPriceValues' => $qualityPriceValues,
                            'qualityWeightValues' => $qualityWeightValues,
                            'qualityWeightUnits' => $qualityWeightUnits,
                            'qualityExistingImages' => $qualityExistingImages,
                        ])

                        @include('admin.quotations.partials.financial-pricing', [
                            'showUnitPrice' => true,
                            'unitPriceValue' => $quotation->unit_price,
                            'commissionValue' => $quotation->commission_service,
                        ])

                        @include('admin.quotations.partials.logistics', [
                            'deliveryCostValue' => $quotation->delivery_cost_china,
                        ])

                        @php $totalQuantity = $quotation->sourcingRequest->destinations->sum('quantity'); @endphp
                        @include('admin.quotations.partials.cost-estimation', [
                            'totalQuantity' => $totalQuantity,
                            'estProductCostValue' => old('estimated_product_cost', $quotation->estimated_product_cost ? $quotation->estimated_product_cost / max(1, $totalQuantity) : null),
                            'estShippingCostValue' => $quotation->estimated_shipping_cost,
                            'estOtherCostsValue' => $quotation->estimated_other_costs,
                        ])

                    </div>
                    
                    <!-- Footer Actions with elegant border and spacing -->
                    <div class="px-6 py-5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-end gap-3 rounded-b-2xl">
                        <a href="{{ route('admin.sourcing-requests.show', $quotation->sourcing_request_id) }}" 
                           class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-slate-900/10 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            {{ __('Update Quotation') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

            <!-- Important Information footer banner -->
            <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 p-1 bg-blue-100 rounded-lg text-blue-600">
                         <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">{{ __('Important Information') }}</h3>
                        <div class="mt-2 text-xs text-blue-700/95 leading-relaxed">
                            <ul class="list-disc pl-5 space-y-1.5">
                                <li>{{ __('Updating this quotation will notify the client.') }}</li>
                                <li>{{ __('The status will be reset to "Quoted".') }}</li>
                                <li>{{ __('Negotiation notes will be preserved for history.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Global photo viewer modal --}}
    <div x-data="{ viewerOpen: false, viewerSrc: '' }"
         @open-viewer.window="viewerSrc = $event.detail.src; viewerOpen = true"
         @keydown.window.escape="viewerOpen = false">
        <template x-teleport="body">
            <div x-show="viewerOpen" x-cloak
                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4"
                 @click="viewerOpen = false">
                <div class="relative max-w-[90vw] max-h-[90vh]" @click.stop>
                    <button type="button" @click="viewerOpen = false"
                        class="absolute -top-3 -right-3 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-slate-900 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img :src="viewerSrc" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
        // Currency signs map
        const currencySymbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'MAD': 'MAD',
            'JPY': '¥',
            'CNY': '¥',
            'CAD': 'CA$',
            'AUD': 'A$',
            'AED': 'AED'
        };

        function getSelectedCurrencySymbol() {
            const currency = document.getElementById('currency').value;
            return currencySymbols[currency] || '$';
        }

        // Toggle estimates section
        document.getElementById('toggle-estimates').addEventListener('click', function() {
            const section = document.getElementById('estimates-section');
            const icon = document.getElementById('toggle-icon');
            const text = document.getElementById('toggle-text');
            
            section.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
            text.textContent = section.classList.contains('hidden') ? '{{ __("Show") }}' : '{{ __("Hide") }}';
        });

        // Update currency symbols across the page
        document.getElementById('currency').addEventListener('change', function() {
            const symbol = getSelectedCurrencySymbol();
            const elements = document.querySelectorAll('.currency-symbol');
            elements.forEach(el => {
                el.textContent = symbol;
            });
            calculateEstimatedProfit();
        });

        // Calculate estimated profit in real-time
        function calculateEstimatedProfit() {
            const totalQuantity = {{ $quotation->sourcingRequest->destinations->sum('quantity') }};
            const unitPrice = parseFloat(document.querySelector('[name="unit_price"]')?.value || 0);
            const commission = parseFloat(document.querySelector('[name="commission_service"]')?.value || 0);
            const deliveryCost = parseFloat(document.querySelector('[name="delivery_cost_china"]')?.value || 0);
            
            const unitProductCost = parseFloat(document.getElementById('estimated_product_cost')?.value || 0);
            const shippingCost = parseFloat(document.getElementById('estimated_shipping_cost')?.value || 0);
            const otherCosts = parseFloat(document.getElementById('estimated_other_costs')?.value || 0);

            const symbol = getSelectedCurrencySymbol();

            // Calculate Total Cost Preview (for user feedback)
            const totalCostPreview = document.getElementById('est-total-cost-preview');
            const estimatedTotalProductCost = unitProductCost * totalQuantity;
            
            if (unitProductCost > 0 && totalQuantity > 0) {
                totalCostPreview.textContent = `{{ __('Scale Total') }}: ~${symbol}${estimatedTotalProductCost.toFixed(2)}`;
            } else {
                totalCostPreview.textContent = '';
            }

            // Calculate TOTALS
            // Revenue = Total
            const totalRevenue = (unitPrice * totalQuantity) + commission + deliveryCost;
            
            // Costs = (Unit Cost * Qty) + Shipping + Others
            const totalCosts = estimatedTotalProductCost + shippingCost + otherCosts;
            
            const profit = totalRevenue - totalCosts;
            const margin = totalRevenue > 0 ? (profit / totalRevenue) * 100 : 0;

            // Show/hide profit preview
            const profitPreview = document.getElementById('profit-preview');
            if (unitProductCost > 0 || shippingCost > 0 || otherCosts > 0) {
                profitPreview.classList.remove('hidden');
                
                // Update values
                document.getElementById('estimated-profit-amount').textContent = 
                    symbol + profit.toFixed(2);
                document.getElementById('estimated-profit-margin').textContent = 
                    margin.toFixed(1) + '%';
                
                // Update progress bar
                const bar = document.getElementById('profit-margin-bar');
                bar.style.width = Math.max(0, Math.min(100, margin)) + '%';
                
                // Color coding and warnings
                const warningEl = document.getElementById('margin-warning');
                if (margin < 10) {
                    bar.className = 'h-full bg-red-500 transition-all duration-300';
                    warningEl.textContent = '⚠️ {{ __("Low margin! Consider adjusting prices.") }}';
                    warningEl.className = 'mt-2 text-xs text-red-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else if (margin < 15) {
                    bar.className = 'h-full bg-amber-500 transition-all duration-300';
                    warningEl.textContent = '⚡ {{ __("Acceptable margin. Review if possible.") }}';
                    warningEl.className = 'mt-2 text-xs text-amber-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else {
                    bar.className = 'h-full bg-emerald-500 transition-all duration-300';
                    warningEl.textContent = '✓ {{ __("Good profit margin!") }}';
                    warningEl.className = 'mt-2 text-xs text-emerald-700 font-semibold';
                    warningEl.classList.remove('hidden');
                }
            } else {
                profitPreview.classList.add('hidden');
            }
        }

        // Attach listeners to pricing fields
        ['unit_price', 'commission_service', 'delivery_cost_china'].forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', calculateEstimatedProfit);
            }
        });

        // Toggle sourcing note visibility
        document.getElementById('actual_sourcing_location').addEventListener('change', function() {
            const container = document.getElementById('sourcing_note_container');
            const requestedLocation = "{{ $quotation->sourcingRequest->sourcing_location }}";
            
            if (this.value !== requestedLocation) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });

        // Image Preview Logic & Size Validation
        const imageInput = document.getElementById('real_product_image');
        const mediaInput = document.getElementById('media_files');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');
        const fileSizeErrorEl = document.getElementById('quotation-file-size-error');
        const maxFileSize = 20971520; // 10MB (10 * 1024 * 1024)
        const fileSizeErrorMsg = '{{ __("Un ou plusieurs fichiers dépassent 20 MB. Veuillez choisir des fichiers plus petits.") }}';

        if (mediaInput) {
            mediaInput.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > maxFileSize) { // 10MB
                            const fileSizeMB = (files[i].size / 1048576).toFixed(2);
                            if (fileSizeErrorEl) {
                                fileSizeErrorEl.classList.remove('hidden');
                                fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: `{{ __('Le fichier') }} "${files[i].name}" {{ __('est trop volumineux') }} (${fileSizeMB} MB). {{ __('Taille maximale : 20 MB.') }}` 
                            }));
                            this.value = '';
                            if (previewContainer && !previewImage.src.includes('storage/')) {
                                previewContainer.classList.add('hidden');
                            }
                            return;
                        }
                    }

                    // Optional: Preview the first image if it's an image
                    const firstFile = files[0];
                    if (firstFile && firstFile.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                        }
                        reader.readAsDataURL(firstFile);
                    }
                }
            });
        }

        // Prevent submit if any file exceeds 10MB (avoids 413 Entity Too Large)
        const quotationForm = document.getElementById('quotation-edit-form');
        if (quotationForm) {
            quotationForm.addEventListener('submit', function(e) {
                if (fileSizeErrorEl) fileSizeErrorEl.classList.add('hidden');
                if (imageInput && imageInput.files.length && imageInput.files[0].size > maxFileSize) {
                    e.preventDefault();
                    const fileSizeMB = (imageInput.files[0].size / 1048576).toFixed(2);
                    if (fileSizeErrorEl) { 
                        fileSizeErrorEl.classList.remove('hidden'); 
                        fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); 
                    }
                    window.dispatchEvent(new CustomEvent('show-error-toast', { 
                        detail: `{{ __('Le fichier image principal dépasse 20 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir un fichier plus petit.') }}` 
                    }));
                    return false;
                }
                if (mediaInput && mediaInput.files.length) {
                    for (let i = 0; i < mediaInput.files.length; i++) {
                        if (mediaInput.files[i].size > maxFileSize) {
                            e.preventDefault();
                            const fileSizeMB = (mediaInput.files[i].size / 1048576).toFixed(2);
                            if (fileSizeErrorEl) { 
                                fileSizeErrorEl.classList.remove('hidden'); 
                                fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); 
                            }
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: `{{ __('Le fichier') }} "${mediaInput.files[i].name}" {{ __('dépasse 20 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir un fichier plus petit.') }}` 
                            }));
                            return false;
                        }
                    }
                }
            });
        }

        // Trigger initial calculation
        window.addEventListener('load', () => {
             calculateEstimatedProfit();
             const symbol = getSelectedCurrencySymbol();
             document.querySelectorAll('.currency-symbol').forEach(el => el.textContent = symbol);
        });
    </script>
    @endpush
</x-app-layout>
