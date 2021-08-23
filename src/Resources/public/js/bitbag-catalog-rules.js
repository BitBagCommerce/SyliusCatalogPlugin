/*
 This file was created by developers working at BitBag
 Do you need more information about us and what we do? Visit our https://bitbag.io website!
 We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

(function() {
    $(document).ready(function () {
        $('.bitbag-rules#product_association_rules a[data-form-collection="add"]').on('click', (event) => {
            const name = $(event.target).closest('form').attr('name');
            setTimeout(() => {
                $(`select[name^="${name}[productAssociationRules]"][name$="[type]"]`).last().change();
            }, 50);
        });

    });
})();
