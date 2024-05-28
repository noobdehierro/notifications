@props([
    'type' => 'success',
    'message' => '',
])


<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    <p class="text-white mb-0">{{ $message }}</p>
</div>
