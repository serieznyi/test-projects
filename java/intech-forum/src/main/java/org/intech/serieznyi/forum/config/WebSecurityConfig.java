package org.intech.serieznyi.forum.config;

import org.intech.serieznyi.forum.security.evaluator.CustomPermissionEvaluator;
import org.intech.serieznyi.forum.security.filter.LoginAndRegisterPagesOnlyForAnonymousUserFilter;
import org.intech.serieznyi.forum.security.web.CustomWebSecurityExpressionHandler;
import org.intech.serieznyi.forum.service.UserService;
import org.springframework.boot.web.servlet.FilterRegistrationBean;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.access.PermissionEvaluator;
import org.springframework.security.config.annotation.authentication.builders.AuthenticationManagerBuilder;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.builders.WebSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configuration.WebSecurityConfigurerAdapter;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.access.expression.DefaultWebSecurityExpressionHandler;

@Configuration
@EnableWebSecurity
public class WebSecurityConfig extends WebSecurityConfigurerAdapter {
    private UserService userService;
    private PasswordEncoder passwordEncoder;

    WebSecurityConfig(
            UserService userService,
            PasswordEncoder passwordEncoder
    ) {
        this.userService = userService;
        this.passwordEncoder = passwordEncoder;
    }

    @Override
    protected void configure(HttpSecurity http) throws Exception {
        http.authorizeRequests()
            // TODO настроить доступ к url по ролям
            .antMatchers("/register").permitAll()
            .antMatchers("/login").permitAll()
            .antMatchers("/public/**").permitAll()
            .antMatchers("/actuator/**").permitAll()
            .anyRequest().fullyAuthenticated();

        http.formLogin()
            .loginPage("/login")
            .defaultSuccessUrl("/", true);

        http.rememberMe()
                .rememberMeCookieName("rememberMe")
                .tokenValiditySeconds(86400)
                .rememberMeParameter("remember-me");

        http.logout()
            .logoutUrl("/logout")
            .logoutSuccessUrl("/")
            .deleteCookies("JSESSIONID")
            .invalidateHttpSession(true)
            .logoutSuccessUrl("/");
    }

    @Override
    protected void configure(AuthenticationManagerBuilder auth) throws Exception {
        auth
                .userDetailsService(userService)
                .passwordEncoder(passwordEncoder)
        ;
    }

    @Bean
    public FilterRegistrationBean<LoginAndRegisterPagesOnlyForAnonymousUserFilter> loggingFilter(){
        FilterRegistrationBean<LoginAndRegisterPagesOnlyForAnonymousUserFilter> loggedFilter = new FilterRegistrationBean<>();
        loggedFilter.setFilter(new LoginAndRegisterPagesOnlyForAnonymousUserFilter());
        loggedFilter.addUrlPatterns("/login", "/register");
        loggedFilter.setOrder(0);

        return loggedFilter;
    }

    @Override
    public void configure(WebSecurity web) {
        CustomWebSecurityExpressionHandler handler = new CustomWebSecurityExpressionHandler();
        handler.setPermissionEvaluator(new CustomPermissionEvaluator());
        web.expressionHandler(handler);
    }
}
