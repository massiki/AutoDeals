@extends('layouts.admin', ['activeMenu' => 'inventory'])

@section('title', 'AutoDeals - Edit Car')

@section('header')
  <div>
    <h2 class="font-bold text-2xl text-foreground hidden sm:block">Edit Vehicle</h2>
    <p class="text-sm text-secondary font-medium hidden sm:block">Update the details of this vehicle in your inventory</p>
  </div>
@endsection

@section('header-actions')
  @parent
  <button type="submit" form="editCarForm"
    class="hidden sm:flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold hover:bg-primary-hover transition-all duration-300 cursor-pointer shadow-sm">
    <i data-lucide="save" class="size-5"></i>
    <span>Update Vehicle</span>
  </button>
@endsection

@section('content')
  <div class="max-w-5xl mx-auto">
    <form id="editCarForm" action="{{ route('cars.update', $car) }}" method="POST" enctype="multipart/form-data"
      class="flex flex-col gap-6">
      @csrf
      @method('PUT')

      <!-- Basic Info Card -->
      <div class="bg-white rounded-3xl border border-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
            <i data-lucide="info" class="size-5 text-primary"></i>
          </div>
          <h3 class="text-lg font-bold text-foreground">Basic Information</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Stock Code (Auto) -->
          <div>
            <label for="inputStockCode" class="form-label">Stock Code</label>
            <div class="relative">
              <i data-lucide="hash" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <input type="text" id="inputStockCode" name="stock_code"
                value="{{ old('stock_code', $car->stock_code) }}" readonly
                class="form-input bg-card-grey text-secondary cursor-not-allowed border-none ring-0"
                style="padding-left:44px">
            </div>
            <p class="text-xs text-secondary mt-1.5 ml-1">Auto-generated identifier</p>
          </div>

          <!-- VIN Number -->
          <div>
            <label for="inputVin" class="form-label">VIN Number <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="barcode" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <input type="text" id="inputVin" name="vin" value="{{ old('vin', $car->vin) }}"
                placeholder="Enter 17-character VIN" class="form-input uppercase @error('vin') ring-error @enderror"
                style="padding-left:44px" maxlength="17">
            </div>
            @error('vin')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Brand -->
          <div>
            <label for="selectBrand" class="form-label">Brand <span class="text-error">*</span></label>
            <select id="selectBrand" name="brand" class="form-input @error('brand') ring-error @enderror">
              <option value="" disabled>Select brand</option>
              <option value="Toyota" @selected(old('brand', $car->brand) == 'Toyota')>Toyota</option>
              <option value="Honda" @selected(old('brand', $car->brand) == 'Honda')>Honda</option>
              <option value="BMW" @selected(old('brand', $car->brand) == 'BMW')>BMW</option>
              <option value="Mercedes-Benz" @selected(old('brand', $car->brand) == 'Mercedes-Benz')>Mercedes-Benz</option>
              <option value="Audi" @selected(old('brand', $car->brand) == 'Audi')>Audi</option>
              <option value="Ford" @selected(old('brand', $car->brand) == 'Ford')>Ford</option>
            </select>
            @error('brand')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Model -->
          <div>
            <label for="inputModel" class="form-label">Model <span class="text-error">*</span></label>
            <input type="text" id="inputModel" name="model" value="{{ old('model', $car->model) }}" placeholder="e.g. Camry SE"
              class="form-input @error('model') ring-error @enderror">
            @error('model')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Year -->
          <div>
            <label for="selectYear" class="form-label">Year <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <select id="selectYear" name="year" class="form-input @error('year') ring-error @enderror"
                style="padding-left:44px">
                <option value="" disabled>Select year</option>
                @for ($y = date('Y') + 1; $y >= 2000; $y--)
                  <option value="{{ $y }}" @selected(old('year', $car->year) == $y)>{{ $y }}</option>
                @endfor
              </select>
            </div>
            @error('year')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Price -->
          <div>
            <label for="inputPrice" class="form-label">Listing Price <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="dollar-sign" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <input type="number" id="inputPrice" name="price" value="{{ old('price', (int) $car->price) }}" placeholder="0"
                class="form-input @error('price') ring-error @enderror" style="padding-left:44px">
            </div>
            @error('price')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Condition -->
          <div class="md:col-span-2">
            <label class="form-label mb-3">Vehicle Condition <span class="text-error">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
              <label
                class="relative flex items-center justify-center p-4 ring-1 ring-border rounded-2xl cursor-pointer hover:bg-card-grey transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 group">
                <input type="radio" name="condition" value="New" class="peer sr-only"
                  @checked(old('condition', $car->condition) == 'New')>
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="sparkles" class="size-6 text-secondary group-has-[:checked]:text-primary"></i>
                  <span class="font-semibold text-secondary group-has-[:checked]:text-primary">New</span>
                </div>
                <div
                  class="absolute top-3 right-3 size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>

              <label
                class="relative flex items-center justify-center p-4 ring-1 ring-border rounded-2xl cursor-pointer hover:bg-card-grey transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 group">
                <input type="radio" name="condition" value="Excellent" class="peer sr-only"
                  @checked(old('condition', $car->condition) == 'Excellent')>
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="shield-check" class="size-6 text-secondary group-has-[:checked]:text-primary"></i>
                  <span class="font-semibold text-secondary group-has-[:checked]:text-primary">Excellent</span>
                </div>
                <div
                  class="absolute top-3 right-3 size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>

              <label
                class="relative flex items-center justify-center p-4 ring-1 ring-border rounded-2xl cursor-pointer hover:bg-card-grey transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 group">
                <input type="radio" name="condition" value="Good" class="peer sr-only"
                  @checked(old('condition', $car->condition) == 'Good')>
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="thumbs-up" class="size-6 text-secondary group-has-[:checked]:text-primary"></i>
                  <span class="font-semibold text-secondary group-has-[:checked]:text-primary">Good</span>
                </div>
                <div
                  class="absolute top-3 right-3 size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>

              <label
                class="relative flex items-center justify-center p-4 ring-1 ring-border rounded-2xl cursor-pointer hover:bg-card-grey transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 group">
                <input type="radio" name="condition" value="Fair" class="peer sr-only"
                  @checked(old('condition', $car->condition) == 'Fair')>
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="alert-circle" class="size-6 text-secondary group-has-[:checked]:text-primary"></i>
                  <span class="font-semibold text-secondary group-has-[:checked]:text-primary">Fair</span>
                </div>
                <div
                  class="absolute top-3 right-3 size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>

              <label
                class="relative flex items-center justify-center p-4 ring-1 ring-border rounded-2xl cursor-pointer hover:bg-card-grey transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:bg-primary/5 group">
                <input type="radio" name="condition" value="Poor" class="peer sr-only"
                  @checked(old('condition', $car->condition) == 'Poor')>
                <div class="flex flex-col items-center gap-2">
                  <i data-lucide="frown" class="size-6 text-secondary group-has-[:checked]:text-primary"></i>
                  <span class="font-semibold text-secondary group-has-[:checked]:text-primary">Poor</span>
                </div>
                <div
                  class="absolute top-3 right-3 size-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-all">
                  <i data-lucide="check" class="size-3 text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
            </div>
            @error('condition')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Specifications Card -->
      <div class="bg-white rounded-3xl border border-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 rounded-xl bg-warning/10 flex items-center justify-center">
            <i data-lucide="settings-2" class="size-5 text-warning"></i>
          </div>
          <h3 class="text-lg font-bold text-foreground">Specifications</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Kilometer -->
          <div>
            <label for="inputKilometer" class="form-label">Mileage (km) <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="gauge" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <input type="number" id="inputKilometer" name="kilometer" value="{{ old('kilometer', $car->kilometer) }}"
                placeholder="0" class="form-input @error('kilometer') ring-error @enderror" style="padding-left:44px">
            </div>
            @error('kilometer')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Color -->
          <div>
            <label for="selectColor" class="form-label">Exterior Color <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="palette" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <select id="selectColor" name="color" class="form-input @error('color') ring-error @enderror"
                style="padding-left:44px">
                <option value="" disabled>Select color</option>
                <option value="Black" @selected(old('color', $car->color) == 'Black')>Black</option>
                <option value="White" @selected(old('color', $car->color) == 'White')>White</option>
                <option value="Silver" @selected(old('color', $car->color) == 'Silver')>Silver</option>
                <option value="Gray" @selected(old('color', $car->color) == 'Gray')>Gray</option>
                <option value="Red" @selected(old('color', $car->color) == 'Red')>Red</option>
                <option value="Blue" @selected(old('color', $car->color) == 'Blue')>Blue</option>
              </select>
            </div>
            @error('color')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Transmission -->
          <div>
            <label for="selectTransmission" class="form-label">Transmission <span class="text-error">*</span></label>
            <select id="selectTransmission" name="transmission"
              class="form-input @error('transmission') ring-error @enderror">
              <option value="" disabled>Select transmission</option>
              <option value="Manual" @selected(old('transmission', $car->transmission) == 'Manual')>Manual</option>
              <option value="Automatic" @selected(old('transmission', $car->transmission) == 'Automatic')>Automatic</option>
              <option value="CVT" @selected(old('transmission', $car->transmission) == 'CVT')>CVT</option>
            </select>
            @error('transmission')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Fuel Type -->
          <div>
            <label for="selectFuel" class="form-label">Fuel Type <span class="text-error">*</span></label>
            <div class="relative">
              <i data-lucide="fuel" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-secondary"></i>
              <select id="selectFuel" name="fuel_type" class="form-input @error('fuel_type') ring-error @enderror"
                style="padding-left:44px">
                <option value="" disabled>Select fuel type</option>
                <option value="Bensin" @selected(old('fuel_type', $car->fuel_type) == 'Bensin')>Bensin</option>
                <option value="Diesel" @selected(old('fuel_type', $car->fuel_type) == 'Diesel')>Diesel</option>
                <option value="Hybrid" @selected(old('fuel_type', $car->fuel_type) == 'Hybrid')>Hybrid</option>
                <option value="Electric" @selected(old('fuel_type', $car->fuel_type) == 'Electric')>Electric</option>
              </select>
            </div>
            @error('fuel_type')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Engine CC -->
          <div>
            <label for="inputEngine" class="form-label">Engine Capacity (CC)</label>
            <input type="number" id="inputEngine" name="engine_cc" value="{{ old('engine_cc', $car->engine_cc) }}"
              placeholder="e.g. 1998" class="form-input @error('engine_cc') ring-error @enderror">
            @error('engine_cc')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Plate Number -->
          <div>
            <label for="inputPlate" class="form-label">License Plate Number</label>
            <input type="text" id="inputPlate" name="plate_number" value="{{ old('plate_number', $car->plate_number) }}"
              placeholder="Enter plate number"
              class="form-input uppercase @error('plate_number') ring-error @enderror">
            @error('plate_number')
              <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Description Card -->
      <div class="bg-white rounded-3xl border border-border p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 rounded-xl bg-success/10 flex items-center justify-center">
            <i data-lucide="file-text" class="size-5 text-success"></i>
          </div>
          <h3 class="text-lg font-bold text-foreground">Vehicle Description</h3>
        </div>

        <div>
          <label for="inputDescription" class="form-label">Detailed Description</label>
          <textarea id="inputDescription" name="description" rows="5"
            placeholder="Enter detailed information about the vehicle's features, history, and condition..."
            class="form-input resize-y min-h-[120px] @error('description') ring-error @enderror">{{ old('description', $car->description) }}</textarea>
          <p class="text-xs text-secondary mt-2 text-right"><span id="charCount">0</span>/1000 characters</p>
          @error('description')
            <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Media Upload Card -->
      <div class="bg-white rounded-3xl border border-border p-6 md:p-8 shadow-sm mb-8">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-border">
          <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
            <i data-lucide="image" class="size-5 text-primary"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-bold text-foreground">Media Gallery</h3>
          </div>
          <span id="imageCount" class="text-sm font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full">0/10
            images</span>
        </div>

        <div class="flex flex-col gap-4">
          <p class="text-sm text-secondary font-medium">Upload up to 10 high-quality images. The first image will be used
            as the main thumbnail.</p>

          <!-- Image Grid Container -->
          <div id="imageGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @if ($car->photos)
              @foreach ($car->photos as $index => $photo)
                <div class="existing-image image-card relative aspect-square rounded-2xl overflow-hidden ring-1 ring-border group shadow-sm" data-photo="{{ $photo }}">
                  @if ($index === 0)
                    <div class="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded-md z-10 shadow-sm">MAIN</div>
                  @endif
                  <img src="{{ Storage::url($photo) }}" alt="Car photo"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                  <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <button type="button" onclick="removeExistingPhoto('{{ $photo }}')"
                      class="size-10 bg-white text-error rounded-full flex items-center justify-center hover:bg-error hover:text-white transition-colors cursor-pointer shadow-lg transform scale-75 group-hover:scale-100 duration-300"
                      title="Remove image">
                      <i data-lucide="trash-2" class="size-5"></i>
                    </button>
                  </div>
                </div>
              @endforeach
            @endif

            <!-- Add Image Button (always last in grid) -->
            <label id="addImageBtn"
              class="aspect-square flex flex-col items-center justify-center gap-3 border-2 border-dashed border-border rounded-2xl hover:border-primary hover:bg-primary/5 transition-all duration-300 cursor-pointer group bg-card-grey/30">
              <div
                class="size-12 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="plus" class="size-6 text-primary"></i>
              </div>
              <span class="text-sm text-secondary font-semibold group-hover:text-primary transition-colors">Add
                Photos</span>
              <input type="file" name="photos[]" accept="image/*" multiple class="hidden"
                onchange="addImages(event)">
            </label>
          </div>
          @error('photos')
            <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
          @enderror
          @error('photos.*')
            <p class="text-xs text-error mt-1.5 ml-1">{{ $message }}</p>
          @enderror
          <!-- Hidden input to track deleted photos -->
          <input type="hidden" name="deleted_photos" id="deletedPhotos" value="">
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pb-8">
        <a href="{{ route('dashboard') }}"
          class="w-full sm:w-auto px-6 py-3.5 rounded-full ring-1 ring-border bg-white text-foreground font-semibold hover:ring-primary transition-all cursor-pointer text-center">
          Cancel
        </a>
        <button type="submit"
          class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-primary text-white font-bold hover:bg-primary-hover transition-all cursor-pointer flex items-center justify-center gap-2 shadow-sm shadow-primary/30">
          <i data-lucide="save" class="size-5"></i>
          Update Vehicle
        </button>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
  <script>
    // Character Counter
    const descInput = document.getElementById('inputDescription');
    const charCount = document.getElementById('charCount');

    if (descInput) {
      descInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
      });
    }

    // Initialize description char count
    if (descInput) {
      charCount.textContent = descInput.value.length;
    }

    // Multiple Image Upload
    let uploadedFiles = [];
    let deletedPhotos = [];
    const MAX_IMAGES = 10;

    function addImages(event) {
      const input = event.target;
      const existingCount = document.querySelectorAll('.existing-image').length;
      const newFiles = Array.from(input.files).slice(uploadedFiles.length);
      const remaining = MAX_IMAGES - (existingCount + uploadedFiles.length);
      const filesToAdd = newFiles.slice(0, remaining);

      if (newFiles.length > remaining) {
        showToast(`You can only add ${remaining} more image(s)`, 'error');
      }

      filesToAdd.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
          uploadedFiles.push({
            id: Date.now() + Math.random().toString(36).substr(2, 9),
            file: file,
            src: e.target.result,
            name: file.name
          });
          renderImageGrid();
        };
        reader.readAsDataURL(file);
      });
    }

    function removeExistingPhoto(photoPath) {
      deletedPhotos.push(photoPath);
      document.getElementById('deletedPhotos').value = JSON.stringify(deletedPhotos);

      const card = document.querySelector(`.existing-image[data-photo="${photoPath}"]`);
      if (card) card.remove();

      updateImageCount();
    }

    function removeImage(imageId) {
      const index = uploadedFiles.findIndex(f => f.id === imageId);
      if (index === -1) return;

      uploadedFiles.splice(index, 1);

      const oldInput = document.querySelector('input[name="photos[]"]');
      const newInput = document.createElement('input');
      newInput.type = 'file';
      newInput.name = 'photos[]';
      newInput.accept = 'image/*';
      newInput.multiple = true;
      newInput.className = 'hidden';

      const dt = new DataTransfer();
      uploadedFiles.forEach(f => dt.items.add(f.file));
      newInput.files = dt.files;
      newInput.addEventListener('change', addImages);

      oldInput.parentNode.replaceChild(newInput, oldInput);

      renderImageGrid();
    }

    function updateImageCount() {
      const existingCount = document.querySelectorAll('.existing-image').length;
      const countEl = document.getElementById('imageCount');
      const addBtn = document.getElementById('addImageBtn');
      const total = existingCount + uploadedFiles.length;

      countEl.textContent = `${total}/${MAX_IMAGES} images`;

      if (total >= MAX_IMAGES) {
        addBtn.classList.add('hidden');
      } else {
        addBtn.classList.remove('hidden');
      }
    }

    function renderImageGrid() {
      const grid = document.getElementById('imageGrid');
      const addBtn = document.getElementById('addImageBtn');

      const existingCards = grid.querySelectorAll('.image-card:not(.existing-image)');
      existingCards.forEach(card => card.remove());

      uploadedFiles.forEach((img, index) => {
        const existingCount = document.querySelectorAll('.existing-image').length;
        const card = document.createElement('div');
        card.className =
          'image-card relative aspect-square rounded-2xl overflow-hidden ring-1 ring-border group shadow-sm';

        const mainBadge = (index === 0 && existingCount === 0) ?
          '<div class="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded-md z-10 shadow-sm">MAIN</div>' :
          '';

        card.innerHTML = `
          ${mainBadge}
          <img src="${img.src}" alt="Uploaded car photo" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
          <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <button type="button" onclick="removeImage('${img.id}')" class="size-10 bg-white text-error rounded-full flex items-center justify-center hover:bg-error hover:text-white transition-colors cursor-pointer shadow-lg transform scale-75 group-hover:scale-100 duration-300" title="Remove image">
              <i data-lucide="trash-2" class="size-5"></i>
            </button>
          </div>
        `;
        grid.insertBefore(card, addBtn);
      });

      updateImageCount();
      lucide.createIcons();
    }
  </script>
@endsection