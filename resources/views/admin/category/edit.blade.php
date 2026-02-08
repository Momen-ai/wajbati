<x-app-layout>

    <div class="flex items-center justify-between mb-4">
        <h2>Edit Category</h2>
    </div>


    <div class="card">
        <div class="card-body">

            <form action="{{ route('dashboard.category.update', $category->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Hospital Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ $category->name }}">
                </div>


                <button type="submit" class="btn btn-primary px-3">Update</button>


            </form>

        </div>
    </div>
</x-app-layout>
