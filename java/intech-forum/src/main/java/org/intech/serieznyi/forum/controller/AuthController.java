package org.intech.serieznyi.forum.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class AuthController {
    @GetMapping("/login")
    public String loginShow() {
        return "page/login";
    }

    @GetMapping("/logout")
    public String makeLogout()
    {
        return "redirect:/";
    }
}
