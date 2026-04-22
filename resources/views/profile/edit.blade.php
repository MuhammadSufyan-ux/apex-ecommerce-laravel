<x-app-layout>
    <div class="bg-[#F9F9F9] min-h-screen py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-black tracking-tight uppercase mb-2">My Profile</h2>
                <p class="text-gray-500 font-medium tracking-wide">Manage your account information and security.</p>
            </div>

            <!-- Profile Info Section -->
            <div class="bg-white border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] rounded-sm overflow-hidden">
                <div class="p-8 md:p-12">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] rounded-sm overflow-hidden">
                <div class="p-8 md:p-12">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Zone Section -->
            @if(false) {{-- Hide if not needed --}}
            <div class="bg-white border border-red-50 shadow-[0_2px_15px_rgba(255,0,0,0.02)] rounded-sm overflow-hidden">
                <div class="p-8 md:p-12">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Small & Focused Cropping Modal -->
    <div id="crop-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeCropModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-none text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-md w-full p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-widest text-center" id="modal-title">Adjust Photo</h3>
                </div>
                <div class="bg-gray-50 overflow-hidden mb-8 border border-gray-100">
                    <img id="crop-image" src="" class="max-w-full block mx-auto">
                </div>
                <div class="flex flex-col gap-3">
                    <button type="button" onclick="applyCrop()" class="w-full py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all border border-black">
                        Apply & Save
                    </button>
                    <button type="button" onclick="closeCropModal()" class="w-full py-4 bg-white border border-gray-200 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] hover:text-black hover:border-black transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const cropModal = document.getElementById('crop-modal');
        const cropImage = document.getElementById('crop-image');
        const profileImageInput = document.getElementById('profile_image_input');
        const profilePreview = document.getElementById('profile-preview');
        const profilePlaceholder = document.getElementById('profile-placeholder');

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    cropImage.src = e.target.result;
                    cropModal.classList.remove('hidden');
                    
                    if (cropper) cropper.destroy();
                    
                    setTimeout(() => {
                        cropper = new Cropper(cropImage, {
                            aspectRatio: 1,
                            viewMode: 2,
                            guides: false,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                        });
                    }, 100);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropModal() {
            cropModal.classList.add('hidden');
            if (cropper) cropper.destroy();
            profileImageInput.value = '';
        }

        function applyCrop() {
            const canvas = cropper.getCroppedCanvas({ width: 500, height: 500 });
            canvas.toBlob((blob) => {
                const file = new File([blob], "profile_image.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                profileImageInput.files = dataTransfer.files;
                profilePreview.src = URL.createObjectURL(blob);
                profilePreview.classList.remove('hidden');
                if (profilePlaceholder) profilePlaceholder.classList.add('hidden');
                cropModal.classList.add('hidden');
            }, 'image/jpeg');
        }
    </script>
    @endpush
</x-app-layout>
