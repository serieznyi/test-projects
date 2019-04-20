package com.moscoding.practice6.controller;

import com.moscoding.practice6.FlashMessageHelper;
import com.moscoding.practice6.domain.entity.Bank;
import com.moscoding.practice6.domain.repository.BankRepository;
import com.moscoding.practice6.service.BankService;
import org.modelmapper.ModelMapper;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.ModelAndView;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.validation.Valid;
import java.util.*;

import static com.moscoding.practice6.PagerHelper.DEFAULT_PAGE_SIZE;

@Controller
public class BankController {
    private BankRepository bankRepository;
    private ModelMapper modelMapper;
    private FlashMessageHelper flashMessageHelper;
    private BankService bankService;

    BankController(
            BankRepository bankRepository,
            ModelMapper modelMapper,
            FlashMessageHelper flashMessageHelper,
            BankService bankService
    ) {
        this.bankRepository = bankRepository;
        this.modelMapper = modelMapper;
        this.flashMessageHelper = flashMessageHelper;
        this.bankService = bankService;
    }

    @GetMapping(path = "/bank/list")
    public String listShow(
            Model model,
            @RequestParam("page") Optional<Integer> pageNumber
    ) {
        int currentPage = pageNumber.orElse(1);

        Page<Bank> page = bankRepository.findAll(
                PageRequest.of(
                        currentPage - 1,
                        DEFAULT_PAGE_SIZE,
                        new Sort(Sort.Direction.ASC, "name")
                )
        );

        model.addAttribute("page", page);

        return "page/bank/list";
    }

    @GetMapping("/bank/create")
    public ModelAndView createShow() {
        return new ModelAndView("page/bank/create", "form", new Bank());
    }

    @PostMapping("/bank/create")
    public String createSubmit(
            @Valid @ModelAttribute("form") Bank form,
            BindingResult bindingResult,
            RedirectAttributes redirectAttributes
    ) {
        if (bindingResult.hasErrors()) {
            return "page/bank/create";
        }

        Bank bank = bankService.createNew(form);

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_created");

        return "redirect:/bank/update/" + bank.getId();
    }

    @GetMapping("/bank/update/{id}")
    public String updateShow(
            @ModelAttribute("form") Bank form,
            Model model,
            @PathVariable Long id
    ) {

        Optional<Bank> bankFromDbOptional = bankRepository.findById(id);

        if (!bankFromDbOptional.isPresent()) {
            return "404";
        }

        modelMapper.map(bankFromDbOptional.get(), form);

        model.addAttribute("form", form);
        model.addAttribute("id", id);

        return "page/bank/update";
    }

    @PostMapping("/bank/update/{id}")
    public String updateSubmit(
            @Valid @ModelAttribute("form") Bank bank,
            Model model,
            BindingResult bindingResult,
            @PathVariable Long id,
            RedirectAttributes redirectAttributes
    ) {
        if (bindingResult.hasErrors()) {
            model.addAttribute("form", bank);
            model.addAttribute("id", id);

            return "page/bank/update";
        }

        if (!bankRepository.findById(id).isPresent()) {
            return "404";
        }

        this.bankRepository.save(bank);

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_updated");

        return "redirect:/bank/update/" + bank.getId();
    }

    @PostMapping("/bank/delete/{id}")
    public String deleteSubmit(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        Optional<Bank> bankOptional = bankRepository.findById(id);
        if (!bankOptional.isPresent()) {
            return "404";
        }

        bankRepository.delete(bankOptional.get());

        flashMessageHelper.addSuccessMessage(redirectAttributes, "flash.entity.successful_deleted");

        return "redirect:/bank/list";
    }
}
