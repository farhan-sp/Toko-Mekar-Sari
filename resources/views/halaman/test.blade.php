<form action="{{ route('test.coba-1') }}" method="POST">
    @csrf
    <div>
        <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
        <select type="number" id="supplier" name="supplier" class="w-full border rounded-md p-2 text-sm" placeholder="Contoh: 10" min="0">
            @foreach ($user as $usaha)
                <option value="{{ $usaha['id_supplier'] }}" name="supplier">{{ $usaha['nama_supplier'] }}</option>
            @endforeach
        </select>

        <button type="submit">Submit</button>
    </div>
</form>