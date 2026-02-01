import Link from 'next/link';

export function SiteHeader() {
  return (
    <header className="border-b border-slate-800 bg-slate-900">
      <div className="mx-auto flex max-w-5xl items-center justify-between p-4">
        <Link href="/" className="text-lg font-semibold text-white">
          SORSU Space
        </Link>
        <nav className="flex gap-4 text-sm text-slate-200">
          <Link href="/subjects">Subjects</Link>
          <Link href="/dashboard">Dashboard</Link>
          <Link href="/productivity">Productivity</Link>
          <Link href="/moderation">Moderation</Link>
          <Link href="/admin">Admin</Link>
        </nav>
      </div>
    </header>
  );
}
