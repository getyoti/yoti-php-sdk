@if ($value instanceof Yoti\Profile\Attribute\MultiValue)
    @foreach ($value as $multiValue)
        @include('partial/attribute', ['value' => $multiValue])
    @endforeach
@elseif ($value instanceof \Yoti\Media\Image)
    <img src="{{ $value->getBase64Content() }}" />
@elseif ($value instanceof \Yoti\Profile\Attribute\DocumentDetails)
    @include('partial/documentdetails', ['documentDetails' => $value])
@elseif ($value instanceof \DateTime) {
    {{ $value->format('d-m-Y') }}
@else
    {{ $value }}
@endif