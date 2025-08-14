@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Dress Code Guidelines</h1>
    
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-700 mb-4">
            To maintain the sanctity and spiritual atmosphere of our sacred temples, all devotees are requested to adhere to the following dress code. Entry may be restricted if attire is deemed inappropriate.
        </p>

        <hr class="my-6">

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">For Men</h3>
            <ul class="list-disc pl-5 space-y-2 text-gray-600">
                <li><strong>Recommended:</strong> Traditional attire such as Dhoti, Kurta, or Pyjamas.</li>
                <li><strong>Permitted:</strong> Simple, full-length trousers and shirts.</li>
                <li><strong>Not Permitted:</strong> Shorts, half-pants, sleeveless t-shirts, vests, or attire with distracting logos or messages.</li>
            </ul>
        </div>

        <hr class="my-6">

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">For Women</h3>
            <ul class="list-disc pl-5 space-y-2 text-gray-600">
                <li><strong>Recommended:</strong> Traditional attire such as Saree, Salwar Kameez, or Lehenga with a dupatta.</li>
                <li><strong>Permitted:</strong> Full-length trousers or skirts with a modest top.</li>
                <li><strong>Not Permitted:</strong> Shorts, skirts above the knee, sleeveless or low-cut tops, tight-fitting or transparent clothing.</li>
            </ul>
        </div>

        <hr class="my-6">

        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">General Instructions for All</h3>
            <ul class="list-disc pl-5 space-y-2 text-gray-600">
                <li>Please remove footwear before entering the main temple premises.</li>
                <li>It is customary to cover your head in certain areas of the temple; please be mindful of local customs.</li>
                <li>We appreciate your cooperation in preserving the spiritual environment of the temple.</li>
            </ul>
        </div>

    </div>
</div>
@endsection
