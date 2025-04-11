<x-filament::modal
    id="media-modal"
    width="4xl"
    :close-button="true"
    :close-by-clicking-away="true"
>
    <x-slot name="heading">
        Post Media
    </x-slot>

    <div class="p-4">
        @if($mediaType === 'image')
            <img src="{{ $mediaUrl }}" alt="Post image" class="max-w-full max-h-[70vh] mx-auto">
        @else
            <video controls class="max-w-full max-h-[70vh] mx-auto">
                <source src="{{ $mediaUrl }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    </div>
</x-filament::modal>

<script>
document.addEventListener('open-media-modal', (event) => {
    const media = event.detail;
    const modal = document.getElementById('media-modal');
    
    // Update modal content based on media type
    const modalContent = modal.querySelector('.p-4');
    if (media.type === 'image') {
        modalContent.innerHTML = `<img src="${media.url}" alt="Post image" class="max-w-full max-h-[70vh] mx-auto">`;
    } else {
        modalContent.innerHTML = `
            <video controls class="max-w-full max-h-[70vh] mx-auto">
                <source src="${media.url}" type="video/mp4">
                Your browser does not support the video tag.
            </video>`;
    }
    
    // Show the modal
    modal.style.display = 'block';
});
</script>