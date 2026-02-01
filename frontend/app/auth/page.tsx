'use client';

import { useAuth } from '../../hooks/use-auth';

export default function AuthPage() {
  const auth = useAuth();

  return (
    <section className="space-y-4">
      <h2 className="text-2xl font-semibold">Sign in / Verify</h2>
      <p className="text-slate-300">Only @sorsu.edu.ph emails are allowed. Verify your email to continue.</p>
      <div className="rounded border border-slate-800 p-4">
        <p className="text-sm text-slate-400">Auth flows will be wired to the Laravel API.</p>
        <button
          className="mt-2 rounded bg-blue-600 px-4 py-2 text-white"
          onClick={() => auth.verify()}
        >
          Mock Verify
        </button>
      </div>
    </section>
  );
}
