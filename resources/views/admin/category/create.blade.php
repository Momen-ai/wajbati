<x-app-layout>

    <div class="flex items-center justify-between mb-4">
        <h2>New Category</h2>
    </div>


    <div class="card">
        <div class="card-body">

            <form action="{{ route('dashboard.category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        placeholder="Enter category name" required>
                </div>


                <button type="submit" class="btn btn-success px-3">Save</button>

            </form>

        </div>
    </div>
</x-app-layout>
