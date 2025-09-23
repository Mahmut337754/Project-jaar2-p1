// Real-time ticket availability updates
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to update ticket availability
    function updateTicketAvailability() {
        const eventId = window.eventId; // We'll set this in the template
        if (!eventId) return;

        fetch(`/api/events/${eventId}/tickets/availability`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update availability for each ticket
                    data.tickets.forEach(ticket => {
                        const ticketCard = document.querySelector(`[data-ticket-id="${ticket.id}"]`);
                        if (ticketCard) {
                            const availabilitySpan = ticketCard.querySelector('.availability-count');
                            const buyButton = ticketCard.querySelector('.buy-button');
                            
                            if (availabilitySpan) {
                                availabilitySpan.textContent = `${ticket.available_quantity} available`;
                                
                                // Update styling based on availability
                                if (ticket.available_quantity === 0) {
                                    availabilitySpan.textContent = 'Sold Out';
                                    availabilitySpan.className = 'availability-count text-red-600';
                                    if (buyButton) {
                                        buyButton.disabled = true;
                                        buyButton.className = 'w-full sm:w-auto bg-gray-400 text-white font-bold py-2 px-4 rounded text-sm cursor-not-allowed order-1 sm:order-2';
                                        buyButton.textContent = 'Sold Out';
                                    }
                                } else if (ticket.available_quantity <= 10) {
                                    availabilitySpan.className = 'availability-count text-yellow-600';
                                } else {
                                    availabilitySpan.className = 'availability-count text-green-600';
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.log('Availability update failed:', error));
    }

    // Update availability every 30 seconds
    if (window.location.pathname.includes('/tickets/')) {
        setInterval(updateTicketAvailability, 30000);
    }

    // Also update when user comes back to the tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && window.location.pathname.includes('/tickets/')) {
            updateTicketAvailability();
        }
    });

    // Live search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit search after 500ms of no typing
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.closest('form').submit();
                }
            }, 500);
        });
    }

    // Quick filter buttons
    const quickFilters = document.querySelectorAll('.quick-filter');
    quickFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const filterType = this.dataset.filter;
            const filterValue = this.dataset.value;
            
            // Update the corresponding form field
            const form = document.querySelector('form');
            const input = form.querySelector(`[name="${filterType}"]`);
            if (input) {
                input.value = filterValue;
                form.submit();
            }
        });
    });
});