<div class="space-y-4">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-orange-50 text-orange-600">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </span>
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Quality Pricing Options') }}</h4>
        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-md uppercase tracking-wider">{{ __('Optional') }}</span>
    </div>

    <p class="text-xs text-slate-500 leading-relaxed">{{ __('Define alternative pricing based on product quality. Client can choose one of these levels.') }}</p>

    <div class="grid grid-cols-1 gap-4">
        @foreach(['low' => ['label' => __('Low Quality (Qualité Faible)'), 'color' => 'amber'], 'medium' => ['label' => __('Medium Quality (Qualité Moyenne)'), 'color' => 'blue'], 'good' => ['label' => __('Good Quality (Qualité Bonne)'), 'color' => 'emerald']] as $key => $info)
            @php 
                $color = $info['color'];
                $colorClass = $color === 'amber' ? 'bg-amber-500' : ($color === 'blue' ? 'bg-blue-500' : 'bg-emerald-500');
                $borderClass = $color === 'amber' ? 'border-amber-100 hover:border-amber-200 bg-amber-50/5' : ($color === 'blue' ? 'border-blue-100 hover:border-blue-200 bg-blue-50/5' : 'border-emerald-100 hover:border-emerald-200 bg-emerald-50/5');
            @endphp
            <div class="border rounded-lg p-4 transition-all {{ $borderClass }} space-y-3.5 shadow-sm">
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $colorClass }} shadow-sm"></span>
                    {{ $info['label'] }}
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Price') }}</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-xs font-semibold currency-symbol">$</span>
<div class="space-y-4">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-orange-50 text-orange-600">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </span>
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Quality Pricing Options') }}</h4>
        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-md uppercase tracking-wider">{{ __('Optional') }}</span>
    </div>

    <p class="text-xs text-slate-500 leading-relaxed">{{ __('Define alternative pricing based on product quality. Client can choose one of these levels.') }}</p>

    <div class="grid grid-cols-1 gap-4">
        @foreach(['low' => ['label' => __('Low Quality (Qualité Faible)'), 'color' => 'amber'], 'medium' => ['label' => __('Medium Quality (Qualité Moyenne)'), 'color' => 'blue'], 'good' => ['label' => __('Good Quality (Qualité Bonne)'), 'color' => 'emerald']] as $key => $info)
            @php 
                $color = $info['color'];
                $colorClass = $color === 'amber' ? 'bg-amber-500' : ($color === 'blue' ? 'bg-blue-500' : 'bg-emerald-500');
                $borderClass = $color === 'amber' ? 'border-amber-100 hover:border-amber-200 bg-amber-50/5' : ($color === 'blue' ? 'border-blue-100 hover:border-blue-200 bg-blue-50/5' : 'border-emerald-100 hover:border-emerald-200 bg-emerald-50/5');
            @endphp
            <div class="border rounded-lg p-4 transition-all {{ $borderClass }} space-y-3.5 shadow-sm">
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $colorClass }} shadow-sm"></span>
                    {{ $info['label'] }}
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Price') }}</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-xs font-semibold currency-symbol">$</span>
                            </div>
                            <input type="number" step="0.01" name="quality_options[{{ $key }}][price]" 
                                value="{{ old('quality_options.'.$key.'.price', $qualityPriceValues[$key] ?? '') }}" 
                                placeholder="0.00" 
                                class="pl-8 block w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-semibold text-slate-800">
                        </div>
                    </div>
                    <div x-data="{ 
                            existingMedia: {{ isset($qualityExistingImages) ? json_encode($qualityExistingImages[$key] ?? []) : '[]' }},
                            removedMedia: [],
                            previews: [],
                            dragging: false,
                            handleFiles(files) {
                                for (let i = 0; i < files.length; i++) {
                                    const file = files[i];
                                    if (file.size > 20971520) {
                                        window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                            detail: `{{ __('Le fichier') }} '${file.name}' {{ __('dépasse 20 MB. Veuillez choisir des fichiers plus petits.') }}`
                                        }));
                                        continue;
                                    }
                                    const isVideo = file.type.startsWith('video/');
                                    const url = URL.createObjectURL(file);
                                    this.previews.push({
                                        name: file.name,
                                        src: url,
                                        file: file,
                                        isVideo: isVideo
                                    });
                                }
                                this.syncFiles();
                            },
                            removePreview(idx) {
                                const preview = this.previews[idx];
                                if (preview.isVideo && preview.src.startsWith('blob:')) {
                                    URL.revokeObjectURL(preview.src);
                                }
                                this.previews.splice(idx, 1);
                                this.syncFiles();
                            },
                            removeExisting(idx) {
                                const path = this.existingMedia[idx];
                                this.removedMedia.push(path);
                                this.existingMedia.splice(idx, 1);
                            },
                            syncFiles() {
                                const dt = new DataTransfer();
                                this.previews.forEach(p => dt.items.add(p.file));
                                this.$refs.fileInput.files = dt.files;
                            },
                            openViewer(src, isVideo = false) {
                                if (!isVideo) {
                                    this.$dispatch('open-viewer', { src });
                                }
                            },
                            getMediaUrl(path) {
                                return `{{ rtrim(config('app.url'), '/') }}/storage/${path}`;
                            },
                            isExistingVideo(path) {
                                const ext = path.split('.').pop().toLowerCase();
                                return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
                            }
                         }" 
                         class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Photos/Videos') }}</label>

                        <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="handleFiles($event.dataTransfer.files); dragging = false"
                             @click="$refs.fileInput.click()"
                             :class="dragging ? 'border-orange-400 bg-orange-50' : 'border-slate-200 bg-white hover:bg-slate-50'"
                             class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all duration-200">
                            <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs font-medium text-slate-500 mt-2" x-text="dragging ? '{{ __('Drop files here...') }}' : '{{ __('Click or drag & drop photos/videos') }}'"></p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ __('Image or Video — max 20MB each') }}</p>
                        </div>

                        <input type="file" x-ref="fileInput" name="quality_options_images[{{ $key }}][]" accept="image/*,video/*" multiple
                            @change="handleFiles($event.target.files)" class="hidden">
                        
                        <template x-for="(path, idx) in removedMedia" :key="'removed-'+idx">
                            <input type="hidden" :name="`delete_quality_images[{{ $key }}][]`" :value="path">
                        </template>

                        <template x-if="previews.length > 0 || existingMedia.length > 0">
                            <div class="flex flex-wrap gap-3 pt-1">
                                <template x-for="(path, idx) in existingMedia" :key="'existing-'+idx">
                                    <div class="relative group w-16 h-16 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50 flex-shrink-0">
                                        <div @click="openViewer(getMediaUrl(path), isExistingVideo(path))" class="w-full h-full" :class="isExistingVideo(path) ? '' : 'cursor-pointer'">
                                            <template x-if="isExistingVideo(path)">
                                                <video :src="getMediaUrl(path)" class="w-full h-full object-cover" muted controls></video>
                                            </template>
                                            <template x-if="!isExistingVideo(path)">
                                                <img :src="getMediaUrl(path)" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!isExistingVideo(path)">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" @click.stop="removeExisting(idx)"
                                            class="absolute -top-1.5 -right-1.5 z-10 w-5 h-5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow flex items-center justify-center opacity-70 hover:opacity-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <template x-for="(preview, idx) in previews" :key="'new-'+idx">
                                    <div class="relative group w-16 h-16 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50 flex-shrink-0">
                                        <div @click="openViewer(preview.src, preview.isVideo)" class="w-full h-full" :class="preview.isVideo ? '' : 'cursor-pointer'">
                                            <template x-if="preview.isVideo">
                                                <video :src="preview.src" class="w-full h-full object-cover" muted controls></video>
                                            </template>
                                            <template x-if="!preview.isVideo">
                                                <img :src="preview.src" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!preview.isVideo">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" @click.stop="removePreview(idx)"
                                            class="absolute -top-1.5 -right-1.5 z-10 w-5 h-5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow flex items-center justify-center opacity-70 hover:opacity-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
