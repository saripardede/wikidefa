@extends('layouts.user')

@section('content')
<style>
    #word-count {
        font-size: 0.875rem;
        color: #4B5563;
        margin-top: 5px;
    }
    #word-count.text-red-500 { color: #EF4444; }
    button.cursor-not-allowed { cursor: not-allowed; opacity: 0.6; }
</style>

<h2 class="text-4xl font-bold ml-0 mb-6">What Was Posted?</h2>

<div class="min-h-screen flex items-start justify-center">
    <div class="bg-black rounded-lg shadow p-6 w-full mx-auto space-y-6 flex flex-col">

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-100 p-4 rounded space-y-4">
            <div class="flex items-center space-x-2 font-semibold">
                <i class="fas fa-user text-purple-700"></i>
                <span>{{ Auth::user()->name }}</span>
            </div>

            <form action="{{ route('tutorial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <label class="block font-semibold">Kategori</label>
                    <select name="kategori" id="kategori" class="w-full mt-1 p-2 border rounded">
                        <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Select Kategori</option>
                        <option value="Baterai" {{ old('kategori') == 'Baterai' ? 'selected' : '' }}>Baterai</option>
                        <option value="AC" {{ old('kategori') == 'AC' ? 'selected' : '' }}>AC</option>
                        <option value="Genset" {{ old('kategori') == 'Genset' ? 'selected' : '' }}>Genset</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold">Judul</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="w-full mt-1 p-2 border rounded" placeholder="Judul tutorial..." />
                </div>

                <div id="tutorial-items" class="space-y-6 mt-6">
                    <div class="flex gap-4 tutorial-item relative">
                        <button type="button" onclick="removeTutorial(this)" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 text-center leading-5 font-bold">×</button>

                        <div class="w-1/3 bg-gray-100 p-4 rounded text-black">
                            <label class="block font-semibold">Upload Gambar atau Video</label>
                            <input type="file" name="media[0][]" class="w-full mb-2" accept="image/*,video/*" multiple onchange="handleMediaUpload(event, 0)" />
                            <div id="preview-container-0" class="mt-4 flex gap-2 flex-wrap"></div>
                        </div>

                        <div class="w-2/3 bg-gray-100 p-6 rounded min-h-[200px]">
                            <textarea name="isi[0]" rows="4" placeholder="Deskripsi tutorial..." class="w-full p-2 rounded resize-none" oninput="countWords(0); checkAllFilled();">{{ old('isi.0') }}</textarea>
                            <p id="word-count-0" class="text-sm text-gray-600 mt-2 text-right">0/5000</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <button type="button" id="add-tutorial" class="bg-white border px-4 py-2 rounded shadow">+ Tambah Tutorial</button>
                    <button id="submit-button" type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded cursor-not-allowed" disabled>Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let tutorialIndex = 1;

    document.getElementById('add-tutorial').addEventListener('click', function () {
        const container = document.getElementById('tutorial-items');

        const html = `
        <div class="flex gap-4 mt-6 tutorial-item relative">
            <button type="button" onclick="removeTutorial(this)" class="absolute -top-3 -right-3 bg-red-600 text-white rounded-full w-6 h-6 text-center leading-5 font-bold shadow">x</button>
            <div class="w-1/3 bg-gray-100 p-4 rounded text-black">
                <label class="block font-semibold">Upload Gambar atau Video</label>
                <input type="file" name="media[${tutorialIndex}][]" class="w-full mb-2" accept="image/*,video/*" multiple onchange="handleMediaUpload(event, ${tutorialIndex})" />
                <div id="preview-container-${tutorialIndex}" class="mt-4 flex gap-2 flex-wrap"></div>
            </div>

            <div class="w-2/3 bg-gray-100 p-6 rounded min-h-[200px]">
                <textarea name="isi[${tutorialIndex}]" rows="4" placeholder="Deskripsi tutorial..." class="w-full p-2 rounded resize-none" oninput="countWords(${tutorialIndex}); checkAllFilled();"></textarea>
                <p id="word-count-${tutorialIndex}" class="text-sm text-gray-600 mt-2 text-right">0/5000</p>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        tutorialIndex++;
    });

    function handleMediaUpload(event, index) {
        const input = event.target;
        const previewContainer = document.getElementById(`preview-container-${index}`);
        if (!previewContainer) return;
        previewContainer.innerHTML = "";

        const files = input.files;
        if (files.length > 0) {
            for (let file of files) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const wrapper = document.createElement("div");
                    wrapper.classList.add("relative", "inline-block", "mr-2", "mb-2");

                    const el = document.createElement(file.type.startsWith("image") ? "img" : "video");
                    el.src = e.target.result;
                    el.classList.add("h-32", "rounded", "border");
                    if (!file.type.startsWith("image")) el.controls = true;

                    const delBtn = document.createElement("button");
                    delBtn.textContent = "×";
                    delBtn.classList.add("absolute", "top-0", "right-0", "bg-red-500", "text-white", "rounded-full", "w-6", "h-6", "text-center", "leading-5", "font-bold");
                    delBtn.onclick = () => wrapper.remove();

                    wrapper.appendChild(el);
                    wrapper.appendChild(delBtn);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function countWords(index) {
        const text = document.querySelector(`#word-count-${index}`).previousElementSibling.value.trim();
        const count = text.split(/\s+/).filter(Boolean).length;
        const max = 5000;
        const el = document.getElementById(`word-count-${index}`);
        el.textContent = `${count}/${max}`;
        el.classList.toggle("text-red-500", count > max);
    }

    function checkFormValidity() {
        const judul = document.getElementById("judul").value.trim();
        const kategori = document.getElementById("kategori").value.trim();
        const items = document.querySelectorAll(".tutorial-item");

        if (!judul || !kategori || items.length === 0) return disableSubmit();

        let isValid = false;
        for (let item of items) {
            const isi = item.querySelector('textarea');
            if (isi?.value.trim()) {
                isValid = true;
                break;
            }
        }
        if (isValid) {
            enableSubmit();
        } else {
            disableSubmit();
        }


        enableSubmit();
    }

    function disableSubmit() {
        const btn = document.getElementById("submit-button");
        btn.disabled = true;
        btn.classList.remove("bg-blue-600", "hover:bg-blue-700");
        btn.classList.add("bg-gray-700", "cursor-not-allowed");
    }

    function enableSubmit() {
        const btn = document.getElementById("submit-button");
        btn.disabled = false;
        btn.classList.remove("bg-gray-700", "cursor-not-allowed");
        btn.classList.add("bg-blue-600", "hover:bg-blue-700");
    }

    document.addEventListener("input", checkFormValidity);
    document.addEventListener("change", checkFormValidity);
    window.addEventListener("DOMContentLoaded", checkFormValidity);

    function removeTutorial(button) {
    const item = button.closest('.tutorial-item');
    if (item) {
        item.remove();
        checkFormValidity(); // biar tombol submit ke-update
    }
}
</script>
@endsection
