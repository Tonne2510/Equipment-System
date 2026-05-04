/**
 * Table Manager - Provides sorting and column visibility features for tables
 */

class TableManager {
    constructor(tableId) {
        this.table = document.getElementById(tableId);
        if (!this.table) {
            console.error(`Table with id "${tableId}" not found`);
            return;
        }
        this.tbody = this.table.querySelector('tbody');
        this.thead = this.table.querySelector('thead');
        this.storageKey = `table_${tableId}_columns`;
        this.sortKey = `table_${tableId}_sort`;
        
        this.init();
    }

    init() {
        this.setupColumnHeaders();
        this.setupColumnToggleButtons();
        this.restoreColumnVisibility();
        this.applySavedSort();
    }

    setupColumnHeaders() {
        const headers = this.thead.querySelectorAll('th');
        headers.forEach((header, index) => {
            // Skip action columns
            if (header.classList.contains('text-center') && header.textContent.includes('Hành')) {
                return;
            }

            // Add sortable class and cursor
            header.style.cursor = 'pointer';
            header.style.userSelect = 'none';
            header.classList.add('sortable-header');
            header.dataset.columnIndex = index;

            // Create sort indicator
            const indicator = document.createElement('span');
            indicator.className = 'sort-indicator';
            indicator.style.marginLeft = '5px';
            indicator.innerHTML = '⇅';
            header.appendChild(indicator);

            // Add click handler for sorting
            header.addEventListener('click', () => this.sortByColumn(index, header));
        });
    }

    setupColumnToggleButtons() {
        const container = this.table.closest('.card') || this.table.parentElement;
        const headerDiv = container.querySelector('.card-header');
        
        if (!headerDiv) return;

        // Create column toggle button
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn btn-sm btn-outline-secondary';
        toggleBtn.innerHTML = '<i class="bi bi-eye-fill"></i> Cột Hiển Thị';
        toggleBtn.style.marginLeft = '10px';
        toggleBtn.addEventListener('click', () => this.showColumnMenu());

        headerDiv.appendChild(toggleBtn);
    }

    showColumnMenu() {
        const headers = this.thead.querySelectorAll('th');
        const menuItems = [];

        headers.forEach((header, index) => {
            // Skip action columns
            if (header.classList.contains('text-center') && header.textContent.includes('Hành')) {
                return;
            }

            const columnName = header.textContent.replace(/⇅/, '').trim();
            const isVisible = this.isColumnVisible(index);

            menuItems.push({
                label: columnName,
                columnIndex: index,
                isVisible: isVisible
            });
        });

        this.renderColumnMenu(menuItems);
    }

    renderColumnMenu(items) {
        // Remove existing menu if any
        const existingMenu = document.getElementById('column-menu');
        if (existingMenu) {
            existingMenu.remove();
        }

        // Create menu container
        const menu = document.createElement('div');
        menu.id = 'column-menu';
        menu.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            min-width: 250px;
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        `;

        const header = document.createElement('div');
        header.style.cssText = 'padding: 10px 15px; border-bottom: 1px solid #eee; font-weight: 600; color: #333;';
        header.textContent = 'Hiển/Ẩn Cột';
        menu.appendChild(header);

        items.forEach(item => {
            const checkbox = document.createElement('label');
            checkbox.style.cssText = `
                display: block;
                padding: 10px 15px;
                cursor: pointer;
                border-bottom: 1px solid #f0f0f0;
                margin-bottom: 0;
            `;

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.checked = item.isVisible;
            input.dataset.columnIndex = item.columnIndex;
            input.addEventListener('change', () => this.toggleColumnVisibility(item.columnIndex));

            checkbox.appendChild(input);
            checkbox.appendChild(document.createTextNode(' ' + item.label));
            menu.appendChild(checkbox);
        });

        document.body.appendChild(menu);

        // Close menu when clicking outside
        setTimeout(() => {
            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !e.target.closest('[data-toggle="column-menu"]')) {
                    menu.remove();
                }
            }, 100);
        });
    }

    toggleColumnVisibility(columnIndex) {
        const headers = this.thead.querySelectorAll('th');
        const rows = this.tbody.querySelectorAll('tr');

        const isVisible = this.isColumnVisible(columnIndex);
        const newVisibility = !isVisible;

        // Update header
        if (headers[columnIndex]) {
            headers[columnIndex].style.display = newVisibility ? '' : 'none';
        }

        // Update all rows
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells[columnIndex]) {
                cells[columnIndex].style.display = newVisibility ? '' : 'none';
            }
        });

        this.saveColumnVisibility(columnIndex, newVisibility);
    }

    isColumnVisible(columnIndex) {
        const headers = this.thead.querySelectorAll('th');
        if (headers[columnIndex]) {
            return headers[columnIndex].style.display !== 'none';
        }
        return true;
    }

    saveColumnVisibility(columnIndex, isVisible) {
        const visibility = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
        visibility[columnIndex] = isVisible;
        localStorage.setItem(this.storageKey, JSON.stringify(visibility));
    }

    restoreColumnVisibility() {
        const visibility = JSON.parse(localStorage.getItem(this.storageKey) || '{}');
        const headers = this.thead.querySelectorAll('th');
        
        headers.forEach((header, index) => {
            if (visibility.hasOwnProperty(index) && !visibility[index]) {
                this.toggleColumnVisibility(index);
            }
        });
    }

    sortByColumn(columnIndex, headerElement) {
        const headers = this.thead.querySelectorAll('th');
        const rows = Array.from(this.tbody.querySelectorAll('tr'));

        // Get current sort direction
        let direction = 'asc';
        if (headerElement.classList.contains('sort-asc')) {
            direction = 'desc';
        }

        // Reset all headers
        headers.forEach(h => {
            h.classList.remove('sort-asc', 'sort-desc');
            const indicator = h.querySelector('.sort-indicator');
            if (indicator) {
                indicator.innerHTML = '⇅';
            }
        });

        // Set current header
        headerElement.classList.add(`sort-${direction}`);
        const indicator = headerElement.querySelector('.sort-indicator');
        if (indicator) {
            indicator.innerHTML = direction === 'asc' ? '↑' : '↓';
        }

        // Sort rows
        rows.sort((a, b) => {
            const aVal = a.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';
            const bVal = b.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';

            // Try numeric sort first
            const aNum = parseFloat(aVal);
            const bNum = parseFloat(bVal);
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return direction === 'asc' ? aNum - bNum : bNum - aNum;
            }

            // Fall back to string sort
            return direction === 'asc' 
                ? aVal.localeCompare(bVal, 'vi')
                : bVal.localeCompare(aVal, 'vi');
        });

        // Reorder rows
        rows.forEach(row => {
            this.tbody.appendChild(row);
        });

        // Save sort state
        this.saveSortState(columnIndex, direction);
    }

    saveSortState(columnIndex, direction) {
        localStorage.setItem(this.sortKey, JSON.stringify({ columnIndex, direction }));
    }

    applySavedSort() {
        const sortState = JSON.parse(localStorage.getItem(this.sortKey) || 'null');
        if (sortState) {
            const headers = this.thead.querySelectorAll('th');
            if (headers[sortState.columnIndex]) {
                // Manually trigger sort
                const header = headers[sortState.columnIndex];
                if (sortState.direction === 'desc') {
                    // Click twice to get to desc
                    this.sortByColumn(sortState.columnIndex, header);
                    this.sortByColumn(sortState.columnIndex, header);
                } else {
                    this.sortByColumn(sortState.columnIndex, header);
                }
            }
        }
    }
}

// Export for use in Blade templates
window.TableManager = TableManager;
