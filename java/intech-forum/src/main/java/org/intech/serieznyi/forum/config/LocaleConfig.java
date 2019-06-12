package org.intech.serieznyi.forum.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.LocaleResolver;
import org.springframework.web.servlet.i18n.FixedLocaleResolver;

import javax.servlet.http.HttpServletRequest;
import java.util.Locale;

@Configuration
public class LocaleConfig {
    private HttpServletRequest httpServletRequest;

    LocaleConfig(HttpServletRequest httpServletRequest) {

        this.httpServletRequest = httpServletRequest;
    }

    @Bean
    public LocaleResolver localeResolver() {
        return new FixedLocaleResolver(Locale.forLanguageTag("ru"));
    }

    @Bean
    public Locale getLocale() {
        return localeResolver().resolveLocale(httpServletRequest);
    }
}
