document.addEventListener('DOMContentLoaded', function () {
    const playersDisplayTab = document.querySelector('#attrib-nxdfm2_players_display');
    if (playersDisplayTab) {
        // wrap the whole content into a div with id flexbox
        const flexbox = document.createElement('div');
        flexbox.id = 'flexbox';
        // get all the content
        const playersDisplayContent = playersDisplayTab.innerHTML;
        // remove the content from the tab
        playersDisplayTab.innerHTML = '';
        // append the content to the flexbox
        flexbox.innerHTML = playersDisplayContent;
        // append the flexbox to the tab
        playersDisplayTab.appendChild(flexbox);
    }
});