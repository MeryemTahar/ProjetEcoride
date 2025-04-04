import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { provideHttpClient, withInterceptorsFromDi, withFetch } from '@angular/common/http';
import { provideRouter } from '@angular/router';
import { routes } from './app/app.routes';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './app/services/auth/auth.interceptor';

bootstrapApplication(AppComponent, {
  providers: [
    // 1. Les routes
    provideRouter(routes),

    // 2. Le HttpClient, + on active l’usage des intercepteurs déclarés en DI
    provideHttpClient(withInterceptorsFromDi(), withFetch()),

    // 3. On déclare l’intercepteur
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true }
  ]
}).catch(err => console.error(err));