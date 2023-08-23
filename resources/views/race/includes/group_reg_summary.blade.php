<tr class="shipping">
    <th>
       Runners
    </th>
    <td>
       5km - {{$race_registration->five_km }} | 10km - {{$race_registration->ten_km }} |21km - {{$race_registration->twenty_one_km }}
    </td>
</tr>
<tr class="shipping">
    <th>
        Tshrts
    </th>
    <td>
        XXL - {{$race_registration->xxl }} | XL- {{$race_registration->xl }} |L- {{$race_registration->l }} |  M- {{$race_registration->m }} | S- {{$race_registration->s }} |XS- {{$race_registration->xs }}
    </td>
</tr>

<tr class="total">
    <th>
        <strong class="text-dark">Race cost</strong>
    </th>
    <td>
        <strong class="text-dark"><span class="amount">{{number_0_format($total_runners * individual_race_cost())}}</span></strong>
    </td>
</tr>

