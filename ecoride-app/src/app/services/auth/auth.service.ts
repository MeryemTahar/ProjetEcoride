import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  // Mise à jour pour utiliser HTTPS
  private baseUrl = 'https://localhost/ProjetEcoride/backend/tests';
  private accessToken: string | null = null;

  constructor(private http: HttpClient) { }

  // Méthode login
  login(email: string, password: string): Observable<any> {
    return this.http.post<any>(
      `${this.baseUrl}/auth.php?action=login`,
      { email, password },
      { withCredentials: true }
    ).pipe(
      tap(response => {
        console.log('Login response:', response);
        if (response.status === 'success') {
          this.accessToken = response.accessToken;
        }
      })
    );
  }

  // Méthode register
  register(userData: any): Observable<any> {
    return this.http.post<any>(
      `${this.baseUrl}/auth.php?action=register`,
      userData,
      { withCredentials: true }
    );
  }

  // Méthode refreshToken
  refreshToken(): Observable<any> {
    return this.http.post<any>(
      `${this.baseUrl}/auth.php?action=refresh`,
      {},
      { withCredentials: true }
    ).pipe(
      tap(response => {
        if (response.status === 'success') {
          this.accessToken = response.accessToken;
        }
      })
    );
  }

  // Méthode logout
  logout(): Observable<any> {
    return this.http.post<any>(
      `${this.baseUrl}/auth.php?action=logout`,
      {},
      { withCredentials: true }
    ).pipe(
      tap(() => {
        this.accessToken = null;
      })
    );
  }

  // Méthode pour récupérer le token d'accès
  getAccessToken(): string | null {
    return this.accessToken;
  }

  // Méthode pour vérifier si l'utilisateur est connecté
  isLoggedIn(): boolean {
    //pour le test uniquiement en local
    return true;
    //pour le test en local et en ligne
    return this.accessToken !== null;
  }
}
