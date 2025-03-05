@props([
    'label' => '',
    'type' => 'text',
    'name' => '',
    'placeholder'=> '',
    'value' => '',
    'text_id'=> 0
])

<div class="w-full relative" id="multi_language_{{ $name }}">
    <label for="{{$name}}_{{$currentLanguage->language_id}}" class="block mb-2 text-sm font-medium text-gray-900">{{ $label }}</label>
    <div class="flex">
        <input type="{{ $type }}" name="{{ $name }}[{{ $currentLanguage->locale }}]"
               id="{{$name}}_{{$currentLanguage->id}}"
               class="rounded-none rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5"
               placeholder="{{ $placeholder }}">
        @if($otherLanguages->count() > 0)
            <span
                data-toggle="language-dropdown-{{ $name }}"
                id="openMultiLanguage"
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-gray-300 border-s-0 rounded-e-md cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"><g
                        fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path
                            d="M7 8.38h4.5m5.5 0h-2.5m-3 0h3m-3 0V7m3 1.38c-.527 1.886-1.632 3.669-2.893 5.236M8.393 17c1.019-.937 2.17-2.087 3.214-3.384m0 0c-.643-.754-1.543-1.973-1.8-2.525m1.8 2.525l1.929 2.005"/><path
                            d="M2.5 12c0-4.478 0-6.718 1.391-8.109S7.521 2.5 12 2.5c4.478 0 6.718 0 8.109 1.391S21.5 7.521 21.5 12c0 4.478 0 6.718-1.391 8.109S16.479 21.5 12 21.5c-4.478 0-6.718 0-8.109-1.391S2.5 16.479 2.5 12"/></g></svg>
            </span>
        @endif
    </div>
    @if($otherLanguages->count() > 0)
        <div id="language_dropdown_{{ $name }}"
             class="hidden mt-2 sm:w-full lg:w-96 bg-white border border-gray-300 rounded-lg shadow-lg z-50">
            @foreach($otherLanguages as $otherLanguage)
                <div class="p-4">
                    <label for="{{ $name }}_{{ $otherLanguage->title }}" class="block mb-2 text-sm font-medium text-gray-900">{{ $label }} ({{ $otherLanguage->title }})</label>
                    <input type="text" name="{{ $name }}[{{ $otherLanguage->locale }}]" id="{{ $name }}_{{$otherLanguage->id}}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                           placeholder="{{ $otherLanguage->title }}">
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    $(document).ready(function () {
        $('[data-toggle="language-dropdown-{{ $name }}"]').on('click', function (e) {
            e.stopPropagation();
            $('#language_dropdown_{{ $name }}').toggleClass('hidden');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#multi_language_{{ $name }}').length) {
                $('#language_dropdown_{{ $name }}').addClass('hidden');
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        @if($text_id)
        $.ajax({
            url: "{{route('languages.translation', $text_id)}}",
            type: "GET",
            beforeSend: function () {
                $('#multi_language_{{ $name }}').LoadingOverlay("show");
            },
            success: function (data) {
                try {
                    $.each(data, function (key, value) {
                        if (value.content)
                            $('input[id="{{$name}}_' + value.language_id + '"]').val(value.content);
                    });
                } catch (e) {
                }
                $('#multi_language_{{ $name }}').LoadingOverlay("hide");
            }
        });
        @endif
    });
</script>

