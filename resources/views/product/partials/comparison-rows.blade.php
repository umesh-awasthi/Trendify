<tr>
    <th>Image</th>
    @foreach($products as $product)
        <td class="text-center">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" 
                     alt="{{ $product->name }}"
                     class="comparison-image">
            @else
                <div class="placeholder-icon">
                    <i class="bi bi-image"></i>
                </div>
            @endif
        </td>
    @endforeach
</tr>
<tr>
    <th>Name</th>
    @foreach($products as $product)
        <td><strong>{{ $product->name }}</strong></td>
    @endforeach
</tr>
<tr>
    <th>Category</th>
    @foreach($products as $product)
        <td>
            @if($product->category)
                <span class="badge bg-secondary">{{ $product->category->name }}</span>
            @else
                <span class="text-muted">N/A</span>
            @endif
        </td>
    @endforeach
</tr>
<tr>
    <th>Price</th>
    @foreach($products as $product)
        <td><strong class="text-primary">${{ number_format($product->price, 2) }}</strong></td>
    @endforeach
</tr>
<tr>
    <th>Short Notes</th>
    @foreach($products as $product)
        <td>{{ $product->short_notes }}</td>
    @endforeach
</tr>
<tr>
    <th>Description</th>
    @foreach($products as $product)
        <td>{{ $product->description }}</td>
    @endforeach
</tr> 