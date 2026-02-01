'use client';

import { useState } from 'react';

export function useAuth() {
  const [token, setToken] = useState<string | null>(null);
  const [verified, setVerified] = useState(false);

  return {
    token,
    verified,
    login: (value: string) => setToken(value),
    verify: () => setVerified(true)
  };
}
