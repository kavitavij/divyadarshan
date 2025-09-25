@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Ebooks</h1>
        <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary">Upload New Ebook</a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>      {{-- NEW COLUMN --}}
                            <th>Price</th>     {{-- NEW COLUMN --}}
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ebooks as $ebook)
                        <tr>
                            <td>
                                @if($ebook->cover_image_path)
                                    <img src="{{ Storage::url($ebook->cover_image_path) }}" height="50" alt="{{ $ebook->title }}" style="object-fit: cover; width: 40px;">
                                @else
                                    <div class="text-muted">No Image</div>
                                @endif
                            </td>
                            <td>{{ $ebook->title }}</td>
                            <td>{{ $ebook->author ?? 'N/A' }}</td>
                            
                            {{-- ======================================================= --}}
                            {{-- START: NEW LOGIC FOR TYPE AND PRICE                   --}}
                            {{-- ======================================================= --}}
                            <td>
                                @if($ebook->type == 'free')
                                    <span class="badge bg-success">Free</span>
                                @else
                                    <span class="badge bg-primary">Paid</span>
                                @endif
                            </td>
                            <td>
                                @if($ebook->type == 'paid')
                                    @if($ebook->discount_percentage > 0)
                                        <div>
                                            <span class="fw-bold text-success">₹{{ number_format($ebook->discounted_price, 2) }}</span>
                                            <span class="badge bg-success ms-1">{{ $ebook->discount_percentage }}% OFF</span>
                                        </div>
                                        <small class="text-muted text-decoration-line-through">₹{{ number_format($ebook->price, 2) }}</small>
                                    @else
                                        <span class="fw-bold">₹{{ number_format($ebook->price, 2) }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            {{-- ======================================================= --}}
                            {{-- END: NEW LOGIC                                        --}}
                            {{-- ======================================================= --}}

                            <td>
                                <form action="{{ route('admin.ebooks.destroy', $ebook->id) }}" method="POST">
                                    <a class="btn btn-sm btn-secondary" href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank" title="View PDF">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.ebooks.edit', $ebook->id) }}" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ebook?')" title="Delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No ebooks found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {!! $ebooks->links() !!}
            </div>
        </div>
    </div>
@endsection