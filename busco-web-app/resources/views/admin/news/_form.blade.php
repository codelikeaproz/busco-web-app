{{-- View: admin/news/_form.blade.php | Purpose: Shared admin news create/edit form partial. --}}

@php($editing = isset($news))
@php($publishDateValue = old('publish_date', $editing && $news->created_at ? $news->created_at->format('Y-m-d') : now()->format('Y-m-d')))

{{-- Main article fields and gallery upload controls --}}
<div class="form-grid" data-admin-news-form-grid style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px;">
    <div class="form-group" style="grid-column:1 / -1;">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" class="form-input" value="{{ old('title', $news->title ?? '') }}" maxlength="255" required>
        @error('title')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category" class="form-input" required>
            <option value="">Select category</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category', $news->category ?? '') === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        @error('category')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="sub_title">Sub-title / Intro Summary (Optional)</label>
        <textarea id="sub_title" name="sub_title" class="form-input" rows="3" maxlength="500" placeholder="Short intro summary shown below the title (recommended to avoid repeating the first paragraph in content).">{{ old('sub_title', $news->sub_title ?? '') }}</textarea>
        @error('sub_title')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-input" required>
            <option value="{{ \App\Models\News::STATUS_DRAFT }}" {{ old('status', $news->status ?? \App\Models\News::STATUS_DRAFT) === \App\Models\News::STATUS_DRAFT ? 'selected' : '' }}>Draft</option>
            <option value="{{ \App\Models\News::STATUS_PUBLISHED }}" {{ old('status', $news->status ?? \App\Models\News::STATUS_DRAFT) === \App\Models\News::STATUS_PUBLISHED ? 'selected' : '' }}>Published</option>
        </select>
        @error('status')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="publish_date">Publish Date</label>
        <input id="publish_date" name="publish_date" type="date" class="form-input" value="{{ $publishDateValue }}">
        {{-- Publish Date reuses the News model created_at value for public display dates. --}}
        @error('publish_date')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="gallery_images">Article Images (Optional, max 5)</label>
        <input id="gallery_images" name="gallery_images[]" type="file" class="form-input" accept=".jpg,.jpeg" multiple data-news-gallery-input>
        <small style="display:block; margin-top:6px; color:#637266;">Accepted formats: JPG/JPEG only. Max 5 images per article.</small>
        @error('gallery_images')
            <span class="form-error">{{ $message }}</span>
        @enderror
        @error('gallery_images.*')
            <span class="form-error">{{ $message }}</span>
        @enderror

        <div data-news-gallery-preview-wrap style="margin-top:12px; display:none;">
            {{-- Client-side preview grid for newly selected uploads (before submit) --}}
            <div style="font-weight:700; color:#1c3d20; margin-bottom:8px;">Selected Images Preview</div>
            <div data-news-gallery-preview-grid style="display:flex; flex-wrap:wrap; gap:10px;"></div>
        </div>

        @if($editing && count($news->gallery_images))
            {{-- Existing stored images with remove checkboxes for update flow --}}
            <div style="margin-top:12px;">
                <div style="font-weight:700; color:#1c3d20; margin-bottom:8px;">Current Images (check to remove)</div>
                <div style="display:flex; flex-wrap:wrap; gap:10px;">
                    @foreach($news->gallery_images as $image)
                        <label style="display:block; width:170px; border:1px solid #e1e9de; border-radius:10px; padding:8px; background:#fbfdf9;">
                            <div style="height:110px; border-radius:8px; overflow:hidden; background:#eff4ec; margin-bottom:8px; display:flex; align-items:center; justify-content:center;">
                                <img src="{{ $image['url'] }}" alt="Article image {{ $loop->iteration }}" style="width:100%; height:100%; object-fit:contain; display:block;">
                            </div>
                            <div style="display:flex; gap:8px; align-items:flex-start;">
                                <input type="checkbox" name="remove_images[]" value="{{ $image['path'] }}" {{ in_array($image['path'], (array) old('remove_images', []), true) ? 'checked' : '' }}>
                                <span style="font-size:.82rem; color:#526456; line-height:1.35;">Remove image {{ $loop->iteration }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label for="content">Content</label>
        <textarea id="content" name="content" class="form-input" rows="10" required>{{ old('content', $news->content ?? '') }}</textarea>
        @error('content')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group" style="grid-column:1 / -1;">
        <label style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $news->is_featured ?? false) ? 'checked' : '' }}>
            <span>Mark as Featured</span>
        </label>
        @error('is_featured')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Mobile layout fallback for the two-column admin form grid --}}
<style>
    @media (max-width: 920px) {
        .admin-panel [data-admin-news-form-grid] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@once
    @push('scripts')
        <script>
            (function () {
                // Client-side gallery preview/remove UX; server still enforces upload rules.
                const input = document.querySelector('[data-news-gallery-input]');
                const previewWrap = document.querySelector('[data-news-gallery-preview-wrap]');
                const previewGrid = document.querySelector('[data-news-gallery-preview-grid]');
                let selectedFiles = [];

                if (!input || !previewWrap || !previewGrid) {
                    return;
                }

                const syncInputFiles = () => {
                    // Rebuild FileList after removing an item from the preview list.
                    if (typeof DataTransfer === 'undefined') {
                        return;
                    }

                    const transfer = new DataTransfer();
                    selectedFiles.forEach((file) => transfer.items.add(file));
                    input.files = transfer.files;
                };

                const createPreviewCard = (file, index) => {
                    // Render one preview tile with image thumbnail + remove button.
                    const card = document.createElement('div');
                    card.style.width = '170px';
                    card.style.border = '1px solid #e1e9de';
                    card.style.borderRadius = '10px';
                    card.style.padding = '8px';
                    card.style.background = '#fbfdf9';
                    card.style.position = 'relative';

                    const imageBox = document.createElement('div');
                    imageBox.style.height = '110px';
                    imageBox.style.borderRadius = '8px';
                    imageBox.style.overflow = 'hidden';
                    imageBox.style.background = '#eff4ec';
                    imageBox.style.marginBottom = '8px';
                    imageBox.style.display = 'flex';
                    imageBox.style.alignItems = 'center';
                    imageBox.style.justifyContent = 'center';

                    const img = document.createElement('img');
                    img.alt = `Selected image ${index + 1}`;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'contain';
                    img.style.display = 'block';

                    const url = URL.createObjectURL(file);
                    img.src = url;
                    img.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });

                    imageBox.appendChild(img);

                    const caption = document.createElement('div');
                    caption.style.fontSize = '.82rem';
                    caption.style.color = '#526456';
                    caption.style.lineHeight = '1.35';
                    caption.style.wordBreak = 'break-word';
                    caption.textContent = file.name;

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.setAttribute('aria-label', `Remove selected image ${index + 1}`);
                    removeBtn.textContent = 'x';
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.top = '6px';
                    removeBtn.style.right = '6px';
                    removeBtn.style.width = '24px';
                    removeBtn.style.height = '24px';
                    removeBtn.style.borderRadius = '999px';
                    removeBtn.style.border = '1px solid #f0c8c4';
                    removeBtn.style.background = '#fff4f3';
                    removeBtn.style.color = '#8d241e';
                    removeBtn.style.fontWeight = '700';
                    removeBtn.style.cursor = 'pointer';
                    removeBtn.style.lineHeight = '1';

                    removeBtn.addEventListener('click', () => {
                        selectedFiles.splice(index, 1);
                        syncInputFiles();
                        renderPreview();
                    });

                    card.appendChild(removeBtn);
                    card.appendChild(imageBox);
                    card.appendChild(caption);

                    return card;
                };

                const renderPreview = () => {
                    // Sync preview UI with the current selectedFiles array.
                    previewGrid.innerHTML = '';

                    if (!selectedFiles.length) {
                        previewWrap.style.display = 'none';
                        return;
                    }

                    selectedFiles.slice(0, 5).forEach((file, index) => {
                        previewGrid.appendChild(createPreviewCard(file, index));
                    });

                    previewWrap.style.display = 'block';
                };

                input.addEventListener('change', () => {
                    selectedFiles = Array.from(input.files || []).slice(0, 5);
                    syncInputFiles();
                    renderPreview();
                });
            })();
        </script>
    @endpush
@endonce
