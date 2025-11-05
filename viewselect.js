export function initViewSelect() {
    const viewselectElement = document.querySelector("[data-view-select]");

    viewselectElement.addEventListener("change", (event) => {
        const selectedView = viewselectElement.value;
        viewselectElement.dispatchEvent(new CustomEvent("view-change", {
            detail: {
                view: selectedView,
                // optionally include selectedDate here if you track it globally
            },
            bubbles: true
        }));
    });
}