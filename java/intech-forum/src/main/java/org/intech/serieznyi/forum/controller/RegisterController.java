package org.intech.serieznyi.forum.controller;

import org.intech.serieznyi.forum.domain.entity.User;
import org.intech.serieznyi.forum.dto.UserRegisterDto;
import org.intech.serieznyi.forum.service.UserService;
import org.intech.serieznyi.forum.util.FlashMessage;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.validation.Valid;

@Controller
public class RegisterController {
    private UserService userService;
    private FlashMessage flashMessage;

    RegisterController(UserService userService, FlashMessage flashMessage) {
        this.userService = userService;
        this.flashMessage = flashMessage;
    }

    @GetMapping("/register")
    public String registerShow(@ModelAttribute("form") UserRegisterDto user, Model model) {
        model.addAttribute("roleList", User.Role.values());

        return "page/register";
    }

    @PostMapping("/register")
    public String registerSubmit(
            @Valid @ModelAttribute("form") UserRegisterDto form,
            BindingResult bindingResult,
            RedirectAttributes redirectAttributes,
            Model model
    ) {

        if (bindingResult.hasErrors()) {
            model.addAttribute("roleList", User.Role.values());

            return "page/register";
        }

        userService.register(form);

        flashMessage.addSuccessMessage(redirectAttributes, "flash.user_successful_created");

        return "redirect:/login";
    }
}
