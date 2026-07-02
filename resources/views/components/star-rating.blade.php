@props(['rating' => 0, 'max' => 5])
<div class="rating rating-sm">
    @for($i = 1; $i <= $max; $i++)
        <input type="radio" class="mask mask-star-2 bg-warning"
               {{ $i <= round($rating) ? 'checked' : '' }} disabled />
    @endfor
</div>