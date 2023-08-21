
@push('after-styles')
    <style>

    </style>

    {{ Html::style(url('vendor/pickr/themes/classic.min.css')) }}

@endpush


<div class="alert alert-info">To set up custom color theme, pick a color of your choice below.</div>
<section class="">
    <input type="text" class="pickr" >
    <div id="color_result">

    </div>
</section>
@push('after-scripts')
    {!! Html::script(url('vendor/pickr/plugins/pickr.min.js')) !!}
    <script>
        const pickr = Pickr.create({
            el: '.pickr',
            theme: 'classic',
            inline: {{$inline}},
            showAlways: {{$showAlways}},
            default: '{{$default}}',
            swatches: [
                'rgba(244, 67, 54, 1)',
                'rgba(233, 30, 99, 0.95)',
                'rgba(156, 39, 176, 0.9)',
                'rgba(103, 58, 183, 0.85)',
                'rgba(63, 81, 181, 0.8)',
                'rgba(33, 150, 243, 0.75)',
                'rgba(3, 169, 244, 0.7)',
                'rgba(0, 188, 212, 0.7)',
                'rgba(0, 150, 136, 0.75)',
                'rgba(76, 175, 80, 0.8)',
                'rgba(139, 195, 74, 0.85)',
                'rgba(205, 220, 57, 0.9)',
                'rgba(255, 235, 59, 0.95)',
                'rgba(255, 193, 7, 1)'
            ],

            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: false,
                    rgba: false,
                    input: true,
                    cancel: false,
                    save: false
                }
            },
            i18n: {
                'btn:cancel': 'Reset',
            }
        });


    </script>
@endpush
