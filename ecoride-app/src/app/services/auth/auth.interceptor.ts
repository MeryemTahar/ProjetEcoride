import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import {
  HttpInterceptor,
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, switchMap } from 'rxjs/operators';
import { AuthService } from '../../services/auth/auth.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  // Injecte PLATFORM_ID pour vérifier le contexte (navigateur vs serveur)
  constructor(
    private authService: AuthService, 
    @Inject(PLATFORM_ID) private platformId: Object
  ) {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Exemple : utiliser isPlatformBrowser pour exécuter du code spécifique au navigateur
    if (isPlatformBrowser(this.platformId)) {
      // Si nécessaire, placer ici le code qui dépend du DOM (document, window, etc.)
    }

    // Récupère l'access token depuis l’AuthService
    const token = this.authService.getAccessToken();
    let authReq = req;
    if (token) {
      authReq = req.clone({
        setHeaders: {
          Authorization: `Bearer ${token}`
        },
        withCredentials: true
      });
    }

    return next.handle(authReq).pipe(
      catchError((error: HttpErrorResponse) => {
        if (error.status === 401) {
          // Tentative de refresh du token
          return this.authService.refreshToken().pipe(
            switchMap((refreshResp: any) => {
              if (refreshResp.status === 'success') {
                const newToken = this.authService.getAccessToken();
                const newReq = req.clone({
                  setHeaders: {
                    Authorization: `Bearer ${newToken}`
                  },
                  withCredentials: true
                });
                return next.handle(newReq);
              } else {
                // Échec du refresh : renvoie l’erreur initiale
                return throwError(() => error);
              }
            })
          );
        }
        return throwError(() => error);
      })
    );
  }
}
