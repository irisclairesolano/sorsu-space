import './globals.css';
import type { ReactNode } from 'react';
import { AppProviders } from '../components/app-providers';
import { SiteHeader } from '../components/site-header';

export const metadata = {
  title: 'SORSU Space',
  description: 'Student-only academic support app for SORSU.'
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <body>
        <AppProviders>
          <SiteHeader />
          <main className="mx-auto max-w-5xl p-6">{children}</main>
        </AppProviders>
      </body>
    </html>
  );
}
