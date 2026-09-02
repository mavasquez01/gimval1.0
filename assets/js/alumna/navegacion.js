document.addEventListener("DOMContentLoaded", function () {

    const params = new URLSearchParams(window.location.search);

    // Get the first GET parameter
    const tabName = params.keys().next().value || "inicio";

    // Find the button that controls that tab
    const tabButton = document.querySelector(
        `[data-bs-target="#${tabName}"]`
    );

    if (tabButton) {
        const tab = new bootstrap.Tab(tabButton);
        tab.show();
    }

});