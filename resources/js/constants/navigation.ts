import type { NavItem, FooterColumn } from '../types/nav';

/**
 * Shared chrome content for SiteHeader/SiteFooter across every real
 * page. Kept to links that actually go somewhere — no placeholder
 * "Offers"/"Contacts"/"Help" entries pointing at "#", since those
 * pages don't exist yet.
 */
export const siteNavItems: NavItem[] = [
    { label: 'Catalog', url: '/catalog' },
    { label: 'Brands', url: '/brands' },
    { label: 'Cart', url: '/cart' },
];

export const siteFooterColumns: FooterColumn[] = [
    {
        title: 'Company',
        links: [{ label: 'About', url: '#' }],
    },
];

export const siteAnnouncement = 'Free delivery from 500 MDL';
