package org.intech.serieznyi.forum.security.filter;

import org.springframework.security.authentication.AnonymousAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

@WebFilter(description = "Redirect authenticate user from login/register page on home page")
public class LoginAndRegisterPagesOnlyForAnonymousUserFilter implements Filter {
    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain) throws IOException, ServletException {
        Authentication auth = SecurityContextHolder.getContext().getAuthentication();

        if (!(auth instanceof AnonymousAuthenticationToken)) {
            ((HttpServletResponse) response).sendRedirect("/");
            return;
        }

        chain.doFilter(request, response);
    }
}
