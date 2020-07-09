// eslint-disable-next-line no-undef
jQuery(document).ready(() => {
    // eslint-disable-next-line no-undef,func-names
    jQuery('.add-another-collection-widget').click(function (e) {
        // eslint-disable-next-line no-undef
        const list = jQuery(jQuery(this)
            .attr('data-list-selector'));
        let counter = list.data('widget-counter') || list.children().length;
        let newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__label__/g, `reponse ${counter}${1}`);
        newWidget = newWidget.replace(/__name__/g, counter);
        // eslint-disable-next-line no-plusplus
        counter++;
        list.data('widget-counter', counter);

        // eslint-disable-next-line no-undef
        const newElem = jQuery(list.attr('data-widget-tags'))
            .html(newWidget);
        newElem.appendTo(list);
    });

    function addTagForm($collectionHolder, $newLinkLi)
    {
        // Get the data-prototype explained earlier
        const prototype = $collectionHolder.data('prototype');

        // eslint-disable-next-line radix
        const index = parseInt($collectionHolder.data('index'));

        let newForm = prototype;

        newForm = newForm.replace(/__name__label__/g, `reponse ${index + 1}`);
        newForm = newForm.replace(/__name__/g, index);

        $collectionHolder.data('index', index + 1);

        // eslint-disable-next-line no-undef
        const $newFormLi = $('<li class="prop-li"></li>')
            .append(newForm);
        $newLinkLi.before($newFormLi);

        // eslint-disable-next-line no-use-before-define
        addTagFormDeleteLink($newFormLi);
    }

    function addTagFormDeleteLink($tagFormLi)
    {
    // eslint-disable-next-line no-undef
        const $removeFormButton = $('<div class="d-flex justify-content-end"><button class="supp-btn-proposition btn btn-round-danger" type="button">Supprimer proposition</button></div>');
        $tagFormLi.append($removeFormButton);

        $removeFormButton.on('click', (e) => {
        // remove the li for the tag form
            $tagFormLi.remove();
        });
    }
    let $collectionHolder;

    // setup an "add a tag" link
    // eslint-disable-next-line no-undef
    const $addTagButton = $('<button type="button" class="btn btn-round-clearblue">Ajouter une proposition</button>');
    // eslint-disable-next-line no-undef
    const $newLinkLi = $('<p></p>').append($addTagButton);


    // eslint-disable-next-line prefer-const,no-undef
    $collectionHolder = $('ul.proposition');

    // eslint-disable-next-line func-names
    $collectionHolder.find('li').each(function () {
        // eslint-disable-next-line no-undef
        addTagFormDeleteLink($(this));
    });
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addTagButton.on('click', (e) => {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });
});
