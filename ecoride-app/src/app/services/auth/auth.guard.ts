import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from './auth.service';

export const authGuardFn: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  // Utilise isLoggedIn() pour vérifier le statut
  if (authService.isLoggedIn()) {
    return true;
  } else {
    // Redirige vers la page de login si l'utilisateur n'est pas connecté
    return router.parseUrl('/login');
  }
};
