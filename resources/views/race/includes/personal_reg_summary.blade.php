<tr class="shipping">
    <th>
        Race type
    </th>
    <td>
        {{code_value()->nameWithNoLang($race_registration->race_type_cv_id) }}
    </td>
</tr>
<tr class="shipping">
    <th>
        Race category
    </th>
    <td>
        {{code_value()->nameWithNoLang($race_registration->race_category_cv_id) }}
    </td>
</tr>

<tr class="shipping">
    <th>
        Tshirt
    </th>
    <td>
        {{code_value()->nameWithNoLang($race_registration->tshirt_type_cv_id) }} || {{code_value()->nameWithNoLang($race_registration->tshirt_size_cv_id) }}
    </td>
</tr>

<tr class="total">
    <th>
        <strong class="text-dark">Race cost</strong>
    </th>
    <td>
        <strong class="text-dark"><span class="amount">{{number_0_format(individual_race_cost())}}</span></strong>
    </td>

</tr>
