import { motion } from "framer-motion";

export default function LaQueVaLogo() {
  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.5, rotate: -180 }}
      animate={{ opacity: 1, scale: 1, rotate: 0 }}
      transition={{
        duration: 1,
        type: "spring",
        stiffness: 100,
        damping: 10,
        delay: 0.2,
      }}
      className="mb-8"
    >
      <img 
        src="/assets/logo.svg" 
        alt="La Que Va Logo" 
        style={{ width: "200px", height: "auto" }}
      />
    </motion.div>
  );
}
