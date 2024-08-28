@extends('layouts.app')

@section('content')
<script>
    window.location.href = "{{ route('list') }}";
</script>
@endsection
